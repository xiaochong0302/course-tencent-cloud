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
        $defaults = $this->getSectionSettings('pay.wxpay');

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
        $config = $this->getDI()->get('config');

        $level = $config->env == ENV_DEV ? 'debug' : 'info';

        $payConfig = [
            'app_id' => $this->settings['app_id'],
            'mch_id' => $this->settings['mch_id'],
            'key' => $this->settings['key'],
            'notify_url' => $this->settings['notify_url'],
            'cert_client' => '',
            'cert_key' => '',
            'log' => [
                'file' => log_path('wxpay.log'),
                'level' => $level,
                'type' => 'daily',
                'max_file' => 30,
            ],
        ];

        if ($config->env == ENV_DEV) {
            $payConfig['mode'] = 'dev';
        }

        return Pay::wechat($payConfig);
    }

}
