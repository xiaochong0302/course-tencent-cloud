<?php

namespace App\Services;

use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Collection;

class Alipay extends Service
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Yansongda\Pay\Gateways\Alipay
     */
    protected $gateway;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('payment.alipay');

        $this->gateway = $this->getGateway();
    }

    /**
     * 查询订单（扫码生成订单后可执行）
     *
     * @param string $outTradeNo
     * @return Collection|bool
     */
    public function findOrder($outTradeNo)
    {
        try {

            $order = ['out_trade_no' => $outTradeNo];

            $result = $this->gateway->find($order);

            return $result;

        } catch (\Exception $e) {

            Log::error('Alipay Find Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * 撤销订单（未生成订单也可执行）
     *
     * @param string $outTradeNo
     * @return Collection|bool
     */
    public function cancelOrder($outTradeNo)
    {
        try {

            $order = ['out_trade_no' => $outTradeNo];

            $result = $this->gateway->cancel($order);

            return $result;

        } catch (\Exception $e) {

            Log::error('Alipay Cancel Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
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

            Log::error('Alipay Close Order Exception', [
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
     *   'refund_amount' => 0.01,
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

            Log::error('Alipay Refund Order Exception', [
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
     *   'total_amount' => 0.01,
     *   'subject' => 'foo',
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

            $result = $response->qr_code ?? false;

            return $result;

        } catch (\Exception $e) {

            Log::error('Alipay Qrcode Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * 处理异步通知
     **/
    public function handleNotify()
    {
        try {

            $data = $this->gateway->verify();

            Log::debug('Alipay Verify Data', $data->all());

        } catch (\Exception $e) {

            Log::error('Alipay Verify Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if ($data->trade_status != 'TRADE_SUCCESS') {
            return false;
        }

        if ($data->app_id != $this->config['app_id']) {
            return false;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data->out_trade_no);

        if (!$trade) {
            return false;
        }

        if ($data->total_amount != $trade->amount) {
            return false;
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->trade_no;

        $this->eventsManager->fire('payment:afterPay', $this, $trade);

        return $this->gateway->success();
    }

    /**
     * 获取 Alipay Gateway
     *
     * @return \Yansongda\Pay\Gateways\Alipay
     */
    public function getGateway()
    {
        $config = $this->getDI()->get('config');

        $level = $config->env == ENV_DEV ? 'debug' : 'info';

        $payConfig = [
            'app_id' => $this->config['app_id'],
            'ali_public_key' => $this->config['public_key'],
            'private_key' => $this->config['private_key'],
            'notify_url' => $this->config['notify_url'],
            'log' => [
                'file' => log_path('alipay.log'),
                'level' => $level,
                'type' => 'daily',
                'max_file' => 30,
            ],
        ];

        if ($config->env == ENV_DEV) {
            $payConfig['mode'] = 'dev';
        }

        $gateway = Pay::alipay($payConfig);

        return $gateway;
    }

}
