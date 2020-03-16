<?php

namespace App\Services;

use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Collection;

class Wechat extends Service
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Yansongda\Pay\Gateways\Wechat
     */
    protected $gateway;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('payment.wechat');

        $this->gateway = $this->getGateway();
    }

    /**
     * 关闭订单（扫码生成订单后可执行）
     *
     * @param string $outTradeNo
     * @return Collection|bool
     */
    public function closeOrder($outTradeNo)
    {
        try {

            $order = ['out_trade_no' => $outTradeNo];

            $result = $this->gateway->close($order);

            return $result;

        } catch (\Exception $e) {

            Log::error('Wechat Close Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * 订单退款
     *
     * <code>
     * $order = [
     *   'out_trade_no' => '1514027114',
     *   'out_refund_no' => '1734027115',
     *   'total_fee' => 1,
     *   'refund_fee' => 1,
     * ];
     * </code>
     *
     * @param array $order
     * @return Collection|bool
     */
    public function refundOrder($order)
    {
        try {

            $result = $this->gateway->refund($order);

            return $result;

        } catch (\Exception $e) {

            Log::error('Wechat Refund Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * 获取二维码内容
     *
     * <code>
     * $order = [
     *   'out_trade_no' =>'1514027114',
     *   'total_fee' => 1,
     *   'body' => 'foo',
     * ];
     *</code>
     *
     * @param array $order
     * @return bool|string
     */
    public function getQrCode($order)
    {
        try {

            $response = $this->gateway->scan($order);

            $result = $response->code_url ?? false;

            return $result;

        } catch (\Exception $e) {

            Log::error('Wechat Scan Error', [
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

            Log::debug('Wechat Verify Data', $data->all());

        } catch (\Exception $e) {

            Log::error('Wechat Verify Error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if ($data->result_code != 'SUCCESS') {
            return false;
        }

        if ($data->mch_id != $this->config['mch_id']) {
            return false;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data->out_trade_no);

        if (!$trade) return false;

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
     * 获取 Wechat Gateway
     *
     * @return \Yansongda\Pay\Gateways\Wechat
     */
    public function getGateway()
    {
        $config = $this->getDI()->get('config');

        $level = $config->env == ENV_DEV ? 'debug' : 'info';

        $payConfig = [
            'app_id' => $this->config['app_id'],
            'mch_id' => $this->config['mch_id'],
            'key' => $this->config['key'],
            'notify_url' => $this->config['notify_url'],
            'log' => [
                'file' => log_path('wechat.log'),
                'level' => $level,
                'type' => 'daily',
                'max_file' => 30,
            ],
        ];

        if ($config->env == ENV_DEV) {
            $payConfig['mode'] = 'dev';
        }

        $gateway = Pay::wechat($payConfig);

        return $gateway;
    }

}
