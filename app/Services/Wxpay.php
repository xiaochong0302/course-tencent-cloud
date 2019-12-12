<?php

namespace App\Services;

use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;

class Wxpay extends Service
{

    protected $config;
    protected $gateway;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('payment.wxpay');
        $this->gateway = $this->getGateway();
    }

    /**
     * 获取二维码内容
     *
     * @param array $order
     * @return bool|string
     */
    public function qrcode($order)
    {
        try {

            $response = $this->gateway->scan($order);

            $result = $response->code_url ?? false;

            return $result;

        } catch (\Exception $e) {

            Log::error('Wxpay Scan Error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * 处理异步通知
     */
    public function notify()
    {
        try {

            $data = $this->gateway->verify();

            Log::debug('Wxpay Verify Data', $data->all());

        } catch (\Exception $e) {

            Log::error('Wxpay Verify Error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if ($data->result_code != 'SUCCESS') {
            return false;
        }

        if ($data->mch_id != $this->config->mch_id) {
            return false;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data->out_trade_no);

        if (!$trade) {
            return false;
        }

        if ($data->total_fee != 100 * $trade->amount) {
            return false;
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->transaction_id;

        $this->eventsManager->fire('payment:afterPay', $this, $trade);

        return $this->gateway->success();
    }

    /**
     * 获取 Wxpay Gateway
     *
     * @return \Yansongda\Pay\Gateways\Wxpay
     */
    public function getGateway()
    {
        $config = [
            'app_id' => $this->config->app_id,
            'mch_id' => $this->config->mch_id,
            'key' => $this->config->key,
            'notify_url' => $this->config->notify_url,
            'log' => [
                'file' => log_path('wxpay.log'),
                'level' => 'debug',
                'type' => 'daily',
                'max_file' => 30,
            ],
            'mode' => 'dev',
        ];

        $gateway = Pay::wxpay($config);

        return $gateway;
    }

}
