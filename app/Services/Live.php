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
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamStateRequest;
use TencentCloud\Live\V20180801\Models\ForbidLiveStreamRequest;
use TencentCloud\Live\V20180801\Models\ResumeLiveStreamRequest;

class Live extends Service
{

    const END_POINT = 'live.tencentcloudapi.com';

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var LiveClient
     */
    protected $client;

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->settings['push'] = $this->getSettings('live.push');
        $this->settings['pull'] = $this->getSettings('live.pull');
        $this->settings['notify'] = $this->getSettings('live.notify');

        $this->logger = $this->getLogger('live');

        $this->client = $this->getLiveClient();
    }

    /**
     * 获取流的状态
     *
     * @param string $streamName
     * @param string $appName
     * @return string|bool
     */
    public function getStreamState($streamName, $appName = 'live')
    {
        try {

            $request = new DescribeLiveStreamStateRequest();

            $params = json_encode([
                'DomainName' => $this->settings['push']['domain'],
                'AppName' => $appName,
                'StreamName' => $streamName,
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Describe Live Stream State Request ' . $params);

            $response = $this->client->DescribeLiveStreamState($request);

            $this->logger->debug('Describe Live Stream State Response ' . $response->toJsonString());

            $result = $response->StreamState;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Describe Live Stream State Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 禁推直播推流
     *
     * @param string $streamName
     * @param string $appName
     * @param string $reason
     * @return array|bool
     */
    public function forbidStream($streamName, $appName = 'live', $reason = '')
    {
        try {

            $request = new ForbidLiveStreamRequest();

            $params = json_encode([
                'DomainName' => $this->settings['push']['domain'],
                'AppName' => $appName,
                'StreamName' => $streamName,
                'Reason' => $reason,
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Forbid Live Stream Request ' . $params);

            $response = $this->client->ForbidLiveStream($request);

            $this->logger->debug('Forbid Live Stream Response ' . $response->toJsonString());

            $result = json_decode($response->toJsonString(), true);

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Forbid Live Stream Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 恢复直播推流
     *
     * @param string $streamName
     * @param string $appName
     * @return array|bool
     */
    public function resumeStream($streamName, $appName = 'live')
    {
        try {

            $request = new ResumeLiveStreamRequest();

            $params = json_encode([
                'DomainName' => $this->settings['push']['domain'],
                'AppName' => $appName,
                'StreamName' => $streamName,
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Resume Live Stream Request ' . $params);

            $response = $this->client->ResumeLiveStream($request);

            $this->logger->debug('Resume Live Stream Response ' . $response->toJsonString());

            $result = json_decode($response->toJsonString(), true);

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Resume Live Stream Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取推流地址
     *
     * @param string $streamName
     * @param string $appName
     * @return string
     */
    public function getPushUrl($streamName, $appName = 'live')
    {
        $authEnabled = $this->settings['push']['auth_enabled'];
        $authKey = $this->settings['push']['auth_key'];
        $expireTime = $this->settings['push']['auth_delta'] + time();
        $domain = $this->settings['push']['domain'];

        $authParams = $this->getAuthParams($streamName, $authKey, $expireTime);

        $pushUrl = "rtmp://{$domain}/{$appName}/{$streamName}";
        $pushUrl .= $authEnabled ? "?{$authParams}" : '';

        return $pushUrl;
    }

    /**
     * 获取拉流地址
     *
     * @param string $streamName
     * @param string $appName
     * @return mixed
     */
    public function getPullUrls($streamName, $appName = 'live')
    {
        $protocol = $this->settings['pull']['protocol'];
        $domain = $this->settings['pull']['domain'];
        $authEnabled = $this->settings['pull']['auth_enabled'];
        $transEnabled = $this->settings['pull']['trans_enabled'];
        $authKey = $this->settings['pull']['auth_key'];
        $expireTime = $this->settings['pull']['auth_delta'] + time();

        $formats = ['rtmp', 'flv', 'm3u8'];

        $urls = [];

        if ($transEnabled) {

            foreach ($formats as $format) {

                foreach (['od', 'hd', 'sd', 'fd'] as $rateName) {

                    $realStreamName = $rateName == 'od' ? $streamName : "{$streamName}_{$rateName}";

                    $authParams = $this->getAuthParams($realStreamName, $authKey, $expireTime);

                    $extension = $format != 'rtmp' ? ".{$format}" : '';
                    $realProtocol = $format != 'rtmp' ? $protocol : 'rtmp';

                    $url = "{$realProtocol}://{$domain}/{$appName}/{$realStreamName}{$extension}";
                    $url .= $authEnabled ? "?{$authParams}" : '';

                    $urls[$format][$rateName] = $url;
                }
            }

        } else {

            foreach ($formats as $format) {

                $authParams = $this->getAuthParams($streamName, $authKey, $expireTime);

                $extension = $format != 'rtmp' ? ".{$format}" : '';
                $realProtocol = $format != 'rtmp' ? $protocol : 'rtmp';

                $url = "{$realProtocol}://{$domain}/{$appName}/{$streamName}{$extension}";
                $url .= $authEnabled ? "?{$authParams}" : '';

                $urls[$format]['od'] = $url;
            }
        }

        return $urls;
    }

    /**
     * 获取鉴权参数
     *
     * @param string $streamName
     * @param string $authKey
     * @param int $expireTime
     * @return string
     */
    protected function getAuthParams($streamName, $authKey, $expireTime)
    {
        $txTime = strtoupper(base_convert($expireTime, 10, 16));

        $txSecret = md5($authKey . $streamName . $txTime);

        return http_build_query([
            'txSecret' => $txSecret,
            'txTime' => $txTime,
        ]);
    }

    protected function getLiveClient()
    {
        $secret = $this->getSettings('secret');

        $secretId = $secret['secret_id'];
        $secretKey = $secret['secret_key'];
        $region = '';

        $credential = new Credential($secretId, $secretKey);

        $httpProfile = new HttpProfile();

        $httpProfile->setEndpoint(self::END_POINT);

        $clientProfile = new ClientProfile();

        $clientProfile->setHttpProfile($httpProfile);

        return new LiveClient($credential, $region, $clientProfile);
    }

}
