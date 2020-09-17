<?php

namespace App\Services\Pay;

use App\Services\Service as Service;
use Yansongda\Pay\Gateways\Wechat;
use Yansongda\Pay\Pay;

class WxpayGateway extends Service
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct($options = [])
    {
        $defaults = $this->getSettings('pay.wxpay');

        $this->settings = array_merge($defaults, $options);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->settings['notify_url'] = $notifyUrl;
    }

    /**
     * @return Wechat
     */
    public function getInstance()
    {
        $config = $this->getConfig();

        $level = $config->get('env') == ENV_DEV ? 'debug' : 'info';

        $options = [
            'appid' => $this->settings['app_id'], // App AppId
            'app_id' => $this->settings['mp_app_id'], // 公众号 AppId
            'miniapp_id' => $this->settings['mini_app_id'], // 小程序 AppId
            'mch_id' => $this->settings['mch_id'],
            'key' => $this->settings['key'],
            'notify_url' => $this->settings['notify_url'],
            'cert_client' => config_path('wxpay/apiclient_cert.pem'),
            'cert_key' => config_path('wxpay/apiclient_key.pem'),
            'log' => [
                'file' => log_path('wxpay.log'),
                'level' => $level,
                'type' => 'daily',
                'max_file' => 30,
            ],
        ];

        if ($config->get('env') == ENV_DEV) {
            $options['mode'] = 'dev';
        }

        return Pay::wechat($options);
    }

}
