<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Pay;

use App\Services\Service;
use Yansongda\Pay\Gateways\Alipay;
use Yansongda\Pay\Pay;

class AlipayGateway extends Service
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct($options = [])
    {
        $defaults = $this->getSettings('pay.alipay');

        $this->settings = array_merge($defaults, $options);
    }

    public function setReturnUrl($returnUrl)
    {
        $this->settings['return_url'] = $returnUrl;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->settings['notify_url'] = $notifyUrl;
    }

    /**
     * @return Alipay
     */
    public function getInstance()
    {
        $config = $this->getConfig();

        $level = $config->get('env') == ENV_DEV ? 'debug' : 'info';

        $options = [
            'app_id' => $this->settings['app_id'],
            'private_key' => $this->settings['private_key'],
            'ali_public_key' => config_path('alipay/alipayCertPublicKey.crt'), // 支付宝公钥证书
            'alipay_root_cert' => config_path('alipay/alipayRootCert.crt'), // 支付宝根证书
            'app_cert_public_key' => config_path('alipay/appCertPublicKey.crt'), // 应用公钥证书
            'notify_url' => $this->settings['notify_url'],
            'return_url' => $this->settings['return_url'],
            'log' => [
                'file' => log_path('alipay.log'),
                'level' => $level,
                'type' => 'daily',
                'max_file' => 30,
            ],
        ];

        if ($config->get('env') == ENV_DEV) {
            $options['mode'] = 'dev';
        }

        return Pay::alipay($options);
    }

}
