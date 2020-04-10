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
     * @param string $format
     * @return mixed
     */
    public function getPullUrls($streamName, $format)
    {
        $extension = ($format == 'hls') ? 'm3u8' : $format;

        $extensions = ['flv', 'm3u8'];

        if (!in_array($extension, $extensions)) {
            return null;
        }

        $appName = 'live';

        $protocol = $this->settings['pull_protocol'];
        $domain = $this->settings['pull_domain'];
        $authEnabled = $this->settings['pull_auth_enabled'];
        $transEnabled = $this->settings['pull_trans_enabled'];
        $authKey = $this->settings['pull_auth_key'];
        $expireTime = $this->settings['pull_auth_delta'] + time();

        $urls = [];

        if ($transEnabled) {

            foreach (['fd', 'sd', 'hd', 'od'] as $rateName) {

                $realStreamName = ($rateName == 'od') ? $streamName : "{$streamName}_{$rateName}";

                $authParams = $this->getAuthParams($realStreamName, $authKey, $expireTime);

                $url = "{$protocol}://{$domain}/{$appName}/{$realStreamName}.{$extension}";
                $url .= $authEnabled ? "?{$authParams}" : '';

                $urls[$rateName] = $url;
            }

        } else {

            $authParams = $this->getAuthParams($streamName, $authKey, $expireTime);

            $url = "{$protocol}://{$domain}/{$appName}/{$streamName}.{$extension}";
            $url .= $authEnabled ? "?{$authParams}" : '';

            $urls['od'] = $url;
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
