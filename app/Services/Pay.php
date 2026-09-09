<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Repos\Trade as TradeRepo;
use Yansongda\Artful\Logger;
use Yansongda\Pay\Pay as YansongdaPay;

abstract class Pay extends Service
{

    public function __construct()
    {
        $this->initPayConfig();
    }

    /**
     * 初始配置
     */
    protected function initPayConfig(): void
    {
        static $initialized = false;

        if ($initialized) return;

        try {

            $config = $this->getConfig();

            $alipaySettings = $this->getSettings('pay.alipay');
            $wxpaySettings = $this->getSettings('pay.wxpay');
            $wechatMpSettings = $this->getSettings('wechat.mp');

            $virtualPay = json_decode($wxpaySettings['virtual_pay'], true);
            $virtualPay['app_secret'] = $wechatMpSettings['app_secret'];
            $virtualPay['sandbox_app_key'] = $config->path('payment.wechat.virtual_pay.sandbox_app_key', '');

            $options = [
                'alipay' => [
                    'default' => [
                        'app_id' => $alipaySettings['app_id'],
                        'app_secret_cert' => $alipaySettings['app_secret_cert'],
                        'app_public_cert_path' => config_path('alipay/appCertPublicKey.crt'),
                        'alipay_public_cert_path' => config_path('alipay/alipayCertPublicKey.crt'),
                        'alipay_root_cert_path' => config_path('alipay/alipayRootCert.crt'),
                        'notify_url' => $alipaySettings['notify_url'],
                        'return_url' => $alipaySettings['return_url'],
                    ]
                ],
                'wechat' => [
                    'default' => [
                        'mch_id' => $wxpaySettings['mch_id'],
                        'app_id' => $wxpaySettings['app_id'],
                        'mp_app_id' => $wxpaySettings['mp_app_id'],
                        'mini_app_id' => $wxpaySettings['mini_app_id'],
                        'mch_secret_key' => $wxpaySettings['mch_secret_key'],
                        'mch_secret_cert' => config_path('wxpay/apiclient_key.pem'),
                        'mch_public_cert_path' => config_path('wxpay/apiclient_cert.pem'),
                        'wechat_public_cert_path' => [
                            $wxpaySettings['wechat_public_key_id'] => config_path('wxpay/wechat_public_key.pem'),
                        ],
                        'virtual_pay' => [
                            'offer_id' => $virtualPay['offer_id'],
                            'app_key' => $virtualPay['app_key'],
                            'app_secret' => $virtualPay['app_secret'],
                            'sandbox_app_key' => $virtualPay['sandbox_app_key'],
                            'callback_token' => $virtualPay['callback_token'],
                            'encoding_aes_key' => $virtualPay['encoding_aes_key'],
                        ],
                        'notify_url' => $wxpaySettings['notify_url'],
                        'return_url' => $wxpaySettings['return_url'],
                    ],
                ],
                'logger' => [
                    'enable' => $config->path('payment.logger.enable', true),
                    'file' => $config->path('payment.logger.file', log_path('pay.log')),
                    'level' => $config->path('payment.logger.level', 'info'),
                    'type' => $config->path('payment.logger.type', 'daily'),
                    'max_file' => $config->path('payment.logger.max_file', 30),
                ],
            ];

            YansongdaPay::config($options);

            $initialized = true;

        } catch (\Exception $e) {

            Logger::error('Pay Config Init Failed: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 交易状态
     */
    public function status(string $tradeNo): int
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($tradeNo);

        return $trade->status;
    }

}
