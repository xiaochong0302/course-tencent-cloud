<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\ConfirmEventsRequest;
use TencentCloud\Vod\V20180717\Models\DeleteMediaRequest;
use TencentCloud\Vod\V20180717\Models\DescribeMediaInfosRequest;
use TencentCloud\Vod\V20180717\Models\DescribeTaskDetailRequest;
use TencentCloud\Vod\V20180717\Models\DescribeTranscodeTemplatesRequest;
use TencentCloud\Vod\V20180717\Models\ProcessMediaRequest;
use TencentCloud\Vod\V20180717\Models\PullEventsRequest;
use TencentCloud\Vod\V20180717\VodClient;

class Vod extends Service
{

    const END_POINT = 'vod.tencentcloudapi.com';

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var VodClient
     */
    protected $client;

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->settings = $this->getSettings('vod');

        $this->logger = $this->getLogger('vod');

        $this->client = $this->getVodClient();
    }

    /**
     * 配置测试
     *
     * @return bool
     */
    public function test()
    {
        try {

            $request = new DescribeTranscodeTemplatesRequest();

            $params = '{}';

            $request->fromJsonString($params);

            $response = $this->client->DescribeTranscodeTemplates($request);

            $this->logger->debug('Describe Transcode Templates Response ' . $response->toJsonString());

            $result = $response->TotalCount > 0;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Describe Transcode Templates Exception ' . kg_json_encode([
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
     * @return string
     */
    public function getUploadSignature()
    {
        $secret = $this->getSettings('secret');

        $secretId = $secret['secret_id'];
        $secretKey = $secret['secret_key'];

        $params = [
            'secretId' => $secretId,
            'currentTimeStamp' => time(),
            'expireTime' => time() + 86400,
            'random' => rand(1000, 9999),
        ];

        $original = http_build_query($params);

        $hash = hash_hmac('SHA1', $original, $secretKey, true);

        return base64_encode($hash . $original);
    }

    /**
     * 获取文件转码
     *
     * @param string $fileId
     * @return array|null
     */
    public function getFileTranscode($fileId)
    {
        if (!$fileId) return null;

        $mediaInfo = $this->getMediaInfo($fileId);

        if (!$mediaInfo) return null;

        $result = [];

        $files = $mediaInfo['MediaInfoSet'][0]['TranscodeInfo']['TranscodeSet'];

        foreach ($files as $file) {

            if ($file['Definition'] == 0) {
                continue;
            }

            $result[] = [
                'url' => $file['Url'],
                'width' => $file['Width'],
                'height' => $file['Height'],
                'definition' => $file['Definition'],
                'duration' => intval($file['Duration']),
                'format' => pathinfo($file['Url'], PATHINFO_EXTENSION),
                'size' => sprintf('%0.2f', $file['Size'] / 1024 / 1024),
                'rate' => intval($file['Bitrate'] / 1024),
            ];
        }

        return $result;
    }

    /**
     * 获取播放地址
     *
     * @param string $url
     * @return string
     */
    public function getPlayUrl($url)
    {
        if ($this->settings['key_anti_enabled'] == 0) {
            return $url;
        }

        $key = $this->settings['key_anti_key'];
        $expiry = $this->settings['key_anti_expiry'] ?: 10800;

        $path = parse_url($url, PHP_URL_PATH);
        $pos = strrpos($path, '/');
        $fileName = substr($path, $pos + 1);
        $dirName = str_replace($fileName, '', $path);

        $expiredTime = base_convert(time() + $expiry, 10, 16);
        $tryTime = 0; // 试看时间，0不限制
        $ipLimit = 9; // ip数量限制，0不限制
        $random = uniqid(); // 随机数

        /**
         * 腾讯坑爹的参数类型和文档，先凑合吧
         * 不限制试看 => 必须exper=0（不能设置为空）
         * 不限制IP => 必须rlimit为空（不能设置为0）
         */
        $myTryTime = $tryTime;
        $myIpLimit = $ipLimit;
        $sign = $key . $dirName . $expiredTime . $myTryTime . $myIpLimit . $random;

        $query = [];

        $query['t'] = $expiredTime;

        $query['exper'] = $myTryTime;

        $query['rlimit'] = $myIpLimit;

        $query['us'] = $random;

        $query['sign'] = md5($sign);

        return $url . '?' . http_build_query($query);
    }

    /**
     * 拉取事件
     *
     * @return bool|array
     */
    public function pullEvents()
    {
        try {

            $request = new PullEventsRequest();

            $params = '{}';

            $request->fromJsonString($params);

            $this->logger->debug('Pull Events Request ' . $params);

            $response = $this->client->PullEvents($request);

            $this->logger->debug('Pull Events Response ' . $response->toJsonString());

            $data = json_decode($response->toJsonString(), true);

            $result = $data['EventSet'] ?? [];

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Pull Events Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 确认事件
     *
     * @param array $eventHandles
     * @return array|bool
     */
    public function confirmEvents($eventHandles)
    {
        try {

            $request = new ConfirmEventsRequest();

            $params = json_encode(['EventHandles' => $eventHandles]);

            $request->fromJsonString($params);

            $this->logger->debug('Confirm Events Request ' . $params);

            $response = $this->client->ConfirmEvents($request);

            $this->logger->debug('Confirm Events Response ' . $response->toJsonString());

            $result = json_decode($response->toJsonString(), true);

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Confirm Events Exception ' . kg_json_encode([
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
     * @param string $fileId
     * @return bool
     */
    public function deleteMedia($fileId)
    {
        try {

            $request = new DeleteMediaRequest();

            $params = json_encode(['FileId' => $fileId]);

            $request->fromJsonString($params);

            $this->logger->debug('Delete Media Request ' . $params);

            $response = $this->client->DeleteMedia($request);

            $this->logger->debug('Delete Media Response ' . $response->toJsonString());

            $result = !empty($response->RequestId);

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Delete Media Exception ' . kg_json_encode([
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
     *
     * @param string $fileId
     * @return array|bool
     */
    public function getMediaInfo($fileId)
    {
        try {

            $request = new DescribeMediaInfosRequest();

            $fileIds = [$fileId];

            $params = json_encode(['FileIds' => $fileIds]);

            $request->fromJsonString($params);

            $this->logger->debug('Describe Media Info Request ' . $params);

            $response = $this->client->DescribeMediaInfos($request);

            $this->logger->debug('Describe Media Info Response ' . $response->toJsonString());

            $result = json_decode($response->toJsonString(), true);

            if (!isset($result['MediaInfoSet'][0]['MetaData'])) {
                return false;
            }

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Describe Media Info Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取任务信息
     *
     * @param string $taskId
     * @return array|bool
     */
    public function getTaskInfo($taskId)
    {
        try {

            $request = new DescribeTaskDetailRequest();

            $params = json_encode(['TaskId' => $taskId]);

            $request->fromJsonString($params);

            $this->logger->debug('Describe Task Detail Request ' . $params);

            $response = $this->client->DescribeTaskDetail($request);

            $this->logger->debug('Describe Task Detail Response ' . $response->toJsonString());

            $result = json_decode($response->toJsonString(), true);

            if (!isset($result['TaskType'])) {
                return false;
            }

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Describe Task Detail Exception ' . kg_json_encode([
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
     *
     * @param string $fileId
     * @return string|bool
     */
    public function createTransVideoTask($fileId)
    {
        $originVideoInfo = $this->getOriginVideoInfo($fileId);

        if (!$originVideoInfo) return false;

        $videoTransTemplates = $this->getVideoTransTemplates();

        $watermarkTemplate = $this->getWatermarkTemplate();

        $transCodeTaskSet = [];

        foreach ($videoTransTemplates as $key => $template) {

            $caseA = $originVideoInfo['height'] >= $template['height'];
            $caseB = $originVideoInfo['bit_rate'] >= 1000 * $template['bit_rate'];

            if ($caseA || $caseB) {

                $item = ['Definition' => $key];

                if ($watermarkTemplate) {
                    $item['WatermarkSet'][] = ['Definition' => $watermarkTemplate];
                }

                $transCodeTaskSet[] = $item;
            }
        }

        /**
         * 无匹配转码模板，取第一项转码
         */
        if (empty($transCodeTaskSet)) {

            $keys = array_keys($videoTransTemplates);

            $item = ['Definition' => $keys[0]];

            if ($watermarkTemplate) {
                $item['WatermarkSet'][] = ['Definition' => $watermarkTemplate];
            }

            $transCodeTaskSet[] = $item;
        }

        $params = json_encode([
            'FileId' => $fileId,
            'MediaProcessTask' => [
                'TranscodeTaskSet' => $transCodeTaskSet,
            ],
        ]);

        try {

            $request = new ProcessMediaRequest();

            $request->fromJsonString($params);

            $this->logger->debug('Process Media Request ' . $params);

            $response = $this->client->ProcessMedia($request);

            $this->logger->debug('Process Media Response ' . $response->toJsonString());

            $result = $response->TaskId ?: false;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Process Media Exception ' . kg_json_encode([
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
     *
     * @param string $fileId
     * @return string|bool
     */
    public function createTransAudioTask($fileId)
    {
        $originAudioInfo = $this->getOriginAudioInfo($fileId);

        if (!$originAudioInfo) return false;

        $audioTransTemplates = $this->getAudioTransTemplates();

        $transCodeTaskSet = [];

        foreach ($audioTransTemplates as $key => $template) {

            if ($originAudioInfo['bit_rate'] >= 1000 * $template['bit_rate']) {

                $item = ['Definition' => $key];

                $transCodeTaskSet[] = $item;
            }
        }

        /**
         * 无匹配转码模板，取第一项转码
         */
        if (empty($transCodeTaskSet)) {

            $keys = array_keys($audioTransTemplates);

            $item = ['Definition' => $keys[0]];

            $transCodeTaskSet[] = $item;
        }

        $params = json_encode([
            'FileId' => $fileId,
            'MediaProcessTask' => [
                'TranscodeTaskSet' => $transCodeTaskSet,
            ],
        ]);

        try {

            $request = new ProcessMediaRequest();

            $request->fromJsonString($params);

            $this->logger->debug('Process Media Request ' . $params);

            $response = $this->client->ProcessMedia($request);

            $this->logger->debug('Process Media Response ' . $response->toJsonString());

            $result = $response->TaskId ?: false;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Process Media Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取原始视频信息
     *
     * @param string $fileId
     * @return array|bool
     */
    public function getOriginVideoInfo($fileId)
    {
        $response = $this->getMediaInfo($fileId);

        if (!$response) return false;

        $metaData = $response['MediaInfoSet'][0]['MetaData'];

        return [
            'bit_rate' => $metaData['Bitrate'],
            'size' => $metaData['Size'],
            'width' => $metaData['Width'],
            'height' => $metaData['Height'],
            'duration' => $metaData['Duration'],
        ];
    }

    /**
     * 获取原始音频信息
     *
     * @param string $fileId
     * @return array|bool
     */
    public function getOriginAudioInfo($fileId)
    {
        $response = $this->getMediaInfo($fileId);

        if (!$response) return false;

        $metaData = $response['MediaInfoSet'][0]['MetaData'];

        return [
            'bit_rate' => $metaData['Bitrate'],
            'size' => $metaData['Size'],
            'width' => $metaData['Width'],
            'height' => $metaData['Height'],
            'duration' => $metaData['Duration'],
        ];
    }

    /**
     * 获取水印模板
     *
     * @return mixed
     */
    public function getWatermarkTemplate()
    {
        $result = null;

        if ($this->settings['wmk_enabled'] == 1 && $this->settings['wmk_tpl_id'] > 0) {
            $result = (int)$this->settings['wmk_tpl_id'];
        }

        return $result;
    }

    /***
     * 获取视频转码模板
     *
     * @return array
     */
    public function getVideoTransTemplates()
    {
        $hlsTemplates = [
            100220 => ['quality' => 'fd', 'height' => 540, 'bit_rate' => 1000, 'frame_rate' => 25],
            100230 => ['quality' => 'sd', 'height' => 720, 'bit_rate' => 1800, 'frame_rate' => 25],
            100240 => ['quality' => 'hd', 'height' => 1080, 'bit_rate' => 2500, 'frame_rate' => 25],
        ];

        $mp4Templates = [
            100020 => ['quality' => 'fd', 'height' => 540, 'bit_rate' => 1000, 'frame_rate' => 25],
            100030 => ['quality' => 'sd', 'height' => 720, 'bit_rate' => 1800, 'frame_rate' => 25],
            100040 => ['quality' => 'hd', 'height' => 1080, 'bit_rate' => 2500, 'frame_rate' => 25],
        ];

        $format = $this->settings['video_format'] ?: 'hls';

        $quality = !empty($this->settings['video_quality']) ? json_decode($this->settings['video_quality'], true) : ['sd'];

        $templates = $format == 'hls' ? $hlsTemplates : $mp4Templates;

        $result = [];

        foreach ($templates as $key => $item) {
            if (in_array($item['quality'], $quality)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * 获取音频转码模板
     *
     * @return array
     */
    public function getAudioTransTemplates()
    {
        $mp3Templates = [
            1010 => ['quality' => 'sd', 'bit_rate' => 128, 'sample_rate' => 44100],
        ];

        $m4aTemplates = [
            1120 => ['quality' => 'sd', 'bit_rate' => 96, 'sample_rate' => 44100],
        ];

        $format = $this->settings['audio_format'] ?: 'mp3';

        $quality = !empty($this->settings['audio_quality']) ? json_decode($this->settings['audio_quality'], true) : ['sd'];

        $templates = $format == 'mp3' ? $mp3Templates : $m4aTemplates;

        $result = [];

        foreach ($templates as $key => $item) {
            if (in_array($item['quality'], $quality)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * 获取VodClient
     *
     * @return VodClient
     */
    public function getVodClient()
    {
        $secret = $this->getSettings('secret');

        $secretId = $secret['secret_id'];
        $secretKey = $secret['secret_key'];

        $region = $this->settings['storage_type'] == 'fixed' ? $this->settings['storage_region'] : '';

        $credential = new Credential($secretId, $secretKey);

        $httpProfile = new HttpProfile();

        $httpProfile->setEndpoint(self::END_POINT);

        $clientProfile = new ClientProfile();

        $clientProfile->setHttpProfile($httpProfile);

        return new VodClient($credential, $region, $clientProfile);
    }

}
