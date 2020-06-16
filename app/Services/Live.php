<?php

namespace App\Services;

class Live extends Service
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct()
    {
        $this->settings = $this->getSectionSettings('live');
    }

    /**
     * 获取推流地址
     *
     * @param string $streamName
     * @return string
     */
    function getPushUrl($streamName)
    {
        $authEnabled = $this->settings['push_auth_enabled'];
        $authKey = $this->settings['push_auth_key'];
        $expireTime = $this->settings['push_auth_delta'] + time();
        $domain = $this->settings['push_domain'];
        $appName = 'live';

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
        $protocol = $this->settings['pull_protocol'];
        $domain = $this->settings['pull_domain'];
        $authEnabled = $this->settings['pull_auth_enabled'];
        $transEnabled = $this->settings['pull_trans_enabled'];
        $authKey = $this->settings['pull_auth_key'];
        $expireTime = $this->settings['pull_auth_delta'] + time();

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
            'txTime' => $txTime
        ]);
    }

}
