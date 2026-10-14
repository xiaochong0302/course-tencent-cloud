<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Logger;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\ConfirmEventsRequest;
use TencentCloud\Vod\V20180717\Models\DeleteMediaRequest;
use TencentCloud\Vod\V20180717\Models\DescribeMediaInfosRequest;
use TencentCloud\Vod\V20180717\Models\DescribeTranscodeTemplatesRequest;
use TencentCloud\Vod\V20180717\Models\MediaInfo;
use TencentCloud\Vod\V20180717\Models\ProcessMediaRequest;
use TencentCloud\Vod\V20180717\Models\PullEventsRequest;
use TencentCloud\Vod\V20180717\VodClient;

class Vod extends Service
{

    /**
     * @var array
     */
    protected array $vodSettings;

    /**
     * @var Logger
     */
    protected Logger $vodLogger;

    /**
     * @var VodClient
     */
    protected VodClient $vodClient;

    public function __construct()
    {
        $this->vodSettings = $this->getSettings('vod');

        $this->vodClient = $this->getVodClient();

        $this->vodLogger = $this->getLogger('vod');
    }

    /**
     * 配置测试
     */
    public function test(): bool
    {
        try {

            $request = new DescribeTranscodeTemplatesRequest();

            $subAppId = (int)$this->vodSettings['sub_app_id'];

            $request->setSubAppId($subAppId);

            $response = $this->vodClient->DescribeTranscodeTemplates($request);

            $this->vodLogger->debug('Describe Transcode Templates Response: ' . $response->toJsonString());

            $result = $response->TotalCount > 0;

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Describe Transcode Templates Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取上传签名
     *
     * @link https://cloud.tencent.com/document/product/266/9221
     */
    public function getUploadSignature(): string
    {
        $secret = $this->getSettings('secret');

        $secretId = $secret['secret_id'];
        $secretKey = $secret['secret_key'];

        $subAppId = (int)$this->vodSettings['sub_app_id'];

        $params = [
            'secretId' => $secretId,
            'vodSubAppId' => $subAppId,
            'currentTimeStamp' => time(),
            'expireTime' => time() + 86400,
            'random' => rand(1000, 9999),
        ];

        $original = http_build_query($params);

        $hash = hash_hmac('SHA1', $original, $secretKey, true);

        return base64_encode($hash . $original);
    }

    /**
     * 获取播放地址
     *
     * @link https://cloud.tencent.com/document/product/266/14047
     */
    public function getPlayUrl(string $url): string
    {
        if ($this->vodSettings['key_anti_enabled'] == 0) {
            return $url;
        }

        $key = $this->vodSettings['key_anti_key'];
        $expiry = (int)$this->vodSettings['key_anti_expiry'];

        $path = parse_url($url, PHP_URL_PATH);
        $pos = strrpos($path, '/');
        $fileName = substr($path, $pos + 1);
        $dirName = str_replace($fileName, '', $path);

        $tryTime = 0; // 试看时间，0不限制
        $expiredTime = dechex(time() + $expiry);
        $ipLimit = (int)$this->vodSettings['key_anti_ip_limit']; // ip数量限制，0不限制
        $random = uniqid(); // 随机数

        /**
         * 腾讯坑爹的参数类型和文档，先凑合吧
         * 不限制试看 => 必须exper=0（不能设置为空）
         * 不限制IP => 必须rlimit为空（不能设置为0）
         */
        $myTryTime = $tryTime;
        $myIpLimit = $ipLimit > 0 && $ipLimit < 10 ? $ipLimit : 9;
        $sign = $key . $dirName . $expiredTime . $myTryTime . $myIpLimit . $random;

        $query = [
            't' => $expiredTime,
            'exper' => $myTryTime,
            'rlimit' => $myIpLimit,
            'us' => $random,
            'sign' => md5($sign),
        ];

        return $url . '?' . http_build_query($query);
    }

    /**
     * 拉取事件
     *
     * @link https://cloud.tencent.com/document/product/266/33433
     */
    public function pullEvents(): array|bool
    {
        try {

            $request = new PullEventsRequest();

            $subAppId = (int)$this->vodSettings['sub_app_id'];

            $request->setSubAppId($subAppId);

            $this->vodLogger->debug('Pull Events Request: ' . $request->toJsonString());

            $response = $this->vodClient->PullEvents($request);

            $this->vodLogger->debug('Pull Events Response: ' . $response->toJsonString());

            $result = $response->getEventSet();

        } catch (TencentCloudSDKException $e) {

            /**
             * 没有事件也会抛异常，避免错误日志困扰，忽略此种情况
             */
            if ($e->getErrorCode() != 'ResourceNotFound') {
                $this->vodLogger->error('Pull Events Exception: ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'code' => $e->getErrorCode(),
                        'message' => $e->getMessage(),
                        'requestId' => $e->getRequestId(),
                    ]));
            }

            $result = false;
        }

        return $result;
    }

    /**
     * 确认事件
     *
     * @link https://cloud.tencent.com/document/product/266/33434
     */
    public function confirmEvents(array $eventHandles): bool
    {
        try {

            $request = new ConfirmEventsRequest();

            $subAppId = (int)$this->vodSettings['sub_app_id'];

            $request->setSubAppId($subAppId);

            $request->setEventHandles($eventHandles);

            $this->vodLogger->debug('Confirm Events Request: ' . $request->toJsonString());

            $response = $this->vodClient->ConfirmEvents($request);

            $this->vodLogger->debug('Confirm Events Response: ' . $response->toJsonString());

            $result = true;

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Confirm Events Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 删除媒体
     *
     * @link https://cloud.tencent.com/document/product/266/31764
     */
    public function deleteMedia(string $fileId, array $deleteParts = []): bool
    {
        try {

            $request = new DeleteMediaRequest();

            $subAppId = (int)$this->vodSettings['sub_app_id'];

            $request->setSubAppId($subAppId);

            $request->setFileId($fileId);

            if (count($deleteParts) > 0) {
                $request->setDeleteParts($deleteParts);
            }

            $this->vodLogger->debug('Delete Media Request: ' . $request->toJsonString());

            $response = $this->vodClient->DeleteMedia($request);

            $this->vodLogger->debug('Delete Media Response: ' . $response->toJsonString());

            $result = true;

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Delete Media Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取媒体信息
     */
    public function getMediaInfo(string $fileId): MediaInfo|bool
    {
        try {

            $request = new DescribeMediaInfosRequest();

            $subAppId = (int)$this->vodSettings['sub_app_id'];

            $request->setSubAppId($subAppId);

            $fileIds = [$fileId];

            $request->setFileIds($fileIds);

            $this->vodLogger->debug('Describe Media Info Request: ' . $request->toJsonString());

            $response = $this->vodClient->DescribeMediaInfos($request);

            $this->vodLogger->debug('Describe Media Info Response: ' . $response->toJsonString());

            if (!$response->getMediaInfoSet()) {
                return false;
            }

            /**
             * @var $result MediaInfo
             */
            $result = $response->getMediaInfoSet()[0];

            if (!$result->getMetaData()) {
                return false;
            }

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Describe Media Info Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 创建视频转码任务
     */
    public function createTransVideoTask(string $fileId): string|bool
    {
        $mediaInfo = $this->getMediaInfo($fileId);

        $metaData = $mediaInfo->getMetaData();

        if (!$metaData) return false;

        $videoTransTemplates = $this->getVideoTransTemplates();

        $watermarkTemplate = $this->getWatermarkTemplateId();

        $transcodeTaskSet = [];

        foreach ($videoTransTemplates as $key => $template) {

            $caseA = $metaData->getHeight() >= $template['height'];
            $caseB = $metaData->getBitrate() >= 1000 * $template['bit_rate'];

            if ($caseA || $caseB) {

                $item = ['Definition' => $key];

                if ($watermarkTemplate) {
                    $item['WatermarkSet'][] = ['Definition' => $watermarkTemplate];
                }

                $transcodeTaskSet[] = $item;
            }
        }

        /**
         * 无匹配转码模板，取第一项转码
         */
        if (empty($transcodeTaskSet)) {

            $keys = array_keys($videoTransTemplates);

            $item = ['Definition' => $keys[0]];

            if ($watermarkTemplate) {
                $item['WatermarkSet'][] = ['Definition' => $watermarkTemplate];
            }

            $transcodeTaskSet[] = $item;
        }

        $subAppId = (int)$this->vodSettings['sub_app_id'];

        $params = json_encode([
            'SubAppId' => $subAppId,
            'FileId' => $fileId,
            'MediaProcessTask' => [
                'TranscodeTaskSet' => $transcodeTaskSet,
            ],
        ]);

        try {

            $request = new ProcessMediaRequest();

            $request->fromJsonString($params);

            $this->vodLogger->debug('Process Media Request: ' . $params);

            $response = $this->vodClient->ProcessMedia($request);

            $this->vodLogger->debug('Process Media Response: ' . $response->toJsonString());

            $result = $response->TaskId ?: false;

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Process Media Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 创建音频转码任务
     */
    public function createTransAudioTask(string $fileId): string|bool
    {
        $mediaInfo = $this->getMediaInfo($fileId);

        $metaData = $mediaInfo->getMetaData();

        if (!$metaData) return false;

        $audioTransTemplates = $this->getAudioTransTemplates();

        $transcodeTaskSet = [];

        foreach ($audioTransTemplates as $key => $template) {

            if ($metaData->getBitrate() >= 1000 * $template['bit_rate']) {

                $item = ['Definition' => $key];

                $transcodeTaskSet[] = $item;
            }
        }

        /**
         * 无匹配转码模板，取第一项转码
         */
        if (empty($transcodeTaskSet)) {

            $keys = array_keys($audioTransTemplates);

            $item = ['Definition' => $keys[0]];

            $transcodeTaskSet[] = $item;
        }

        $subAppId = (int)$this->vodSettings['sub_app_id'];

        $params = json_encode([
            'SubAppId' => $subAppId,
            'FileId' => $fileId,
            'MediaProcessTask' => [
                'TranscodeTaskSet' => $transcodeTaskSet,
            ],
        ]);

        try {

            $request = new ProcessMediaRequest();

            $request->fromJsonString($params);

            $this->vodLogger->debug('Process Media Request: ' . $params);

            $response = $this->vodClient->ProcessMedia($request);

            $this->vodLogger->debug('Process Media Response: ' . $response->toJsonString());

            $result = $response->TaskId ?: false;

        } catch (TencentCloudSDKException $e) {

            $this->vodLogger->error('Process Media Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取水印模板
     */
    public function getWatermarkTemplateId(): ?int
    {
        $result = null;

        if ($this->vodSettings['wmk_enabled'] == 1 && $this->vodSettings['wmk_tpl_id'] > 0) {
            $result = (int)$this->vodSettings['wmk_tpl_id'];
        }

        return $result;
    }

    /***
     * 获取视频转码模板
     *
     * @return array
     */
    public function getVideoTransTemplates(): array
    {
        $normalTemplates = [
            'hls' => [
                100220 => ['quality' => 'fd', 'height' => 540, 'bit_rate' => 1000, 'frame_rate' => 25],
                100230 => ['quality' => 'sd', 'height' => 720, 'bit_rate' => 1800, 'frame_rate' => 25],
                100240 => ['quality' => 'hd', 'height' => 1080, 'bit_rate' => 2500, 'frame_rate' => 25],
            ],
            'mp4' => [
                100020 => ['quality' => 'fd', 'height' => 540, 'bit_rate' => 1000, 'frame_rate' => 25],
                100030 => ['quality' => 'sd', 'height' => 720, 'bit_rate' => 1800, 'frame_rate' => 25],
                100040 => ['quality' => 'hd', 'height' => 1080, 'bit_rate' => 2500, 'frame_rate' => 25],
            ]
        ];

        /**
         * 极速高清暂时没有预设HLS封装
         */
        $tesTemplates = [
            'mp4' => [
                100820 => ['quality' => 'fd', 'height' => 540, 'bit_rate' => 1000, 'frame_rate' => 25],
                100830 => ['quality' => 'sd', 'height' => 720, 'bit_rate' => 1800, 'frame_rate' => 25],
                100840 => ['quality' => 'hd', 'height' => 1080, 'bit_rate' => 2500, 'frame_rate' => 25],
            ]
        ];

        $format = $this->vodSettings['video_format'] ?: 'hls';

        if ($this->vodSettings['transcode_type'] == 'tes') {
            $templates = $tesTemplates[$format] ?? $tesTemplates['mp4'];
        } else {
            $templates = $normalTemplates[$format] ?? $normalTemplates['hls'];
        }

        $quality = ['sd'];

        if (!empty($this->vodSettings['video_quality'])) {
            $quality = json_decode($this->vodSettings['video_quality'], true);
        }

        return array_filter($templates, function ($item) use ($quality) {
            return in_array($item['quality'], $quality);
        });
    }

    /**
     * 获取音频转码模板
     */
    public function getAudioTransTemplates(): array
    {
        $mp3Templates = [
            1010 => ['quality' => 'sd', 'bit_rate' => 128, 'sample_rate' => 44100],
        ];

        $m4aTemplates = [
            1120 => ['quality' => 'sd', 'bit_rate' => 96, 'sample_rate' => 44100],
        ];

        $format = $this->vodSettings['audio_format'] ?: 'mp3';

        $quality = !empty($this->vodSettings['audio_quality']) ? json_decode($this->vodSettings['audio_quality'], true) : ['sd'];

        $templates = $format == 'mp3' ? $mp3Templates : $m4aTemplates;

        return array_filter($templates, function ($item) use ($quality) {
            return in_array($item['quality'], $quality);
        });
    }

    /**
     * 获取VodClient
     */
    public function getVodClient(): VodClient
    {
        $secret = $this->getSettings('secret');

        $credential = new Credential($secret['secret_id'], $secret['secret_key']);

        $httpProfile = new HttpProfile();

        $clientProfile = new ClientProfile();

        $clientProfile->setHttpProfile($httpProfile);

        $region = $secret['region'] ?? 'ap-guangzhou';

        return new VodClient($credential, $region, $clientProfile);
    }

}
