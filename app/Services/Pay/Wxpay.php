<?php

namespace App\Services\Pay;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use App\Services\Pay as PayService;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Yansongda\Pay\Gateways\Wechat as WechatGateway;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Collection;

class Wxpay extends PayService
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct()
    {
        $this->settings = $this->getSectionSettings('pay.wxpay');
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->settings['notify_url'] = $notifyUrl;
    }

    /**
     * 扫码下单
     *
     * @param TradeModel $trade
     * @return bool|string
     */
    public function scan(TradeModel $trade)
    {
        $gateway = $this->getGateway();

        try {

            $response = $gateway->scan([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

            $result = $response->code_url ?? false;

        } catch (\Exception $e) {

            Log::error('Wxpay Scan Error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 移动端支付
     *
     * @param TradeModel $trade
     * @return HttpResponse|bool
     */
    public function wap(TradeModel $trade)
    {
        $gateway = $this->getGateway();

        try {

            return $gateway->wap([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Log::error('Wxpay Wap Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 异步通知
     *
     * @return HttpResponse|bool
     */
    public function notify()
    {
        $gateway = $this->getGateway();

        try {

            $data = $gateway->verify();

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

        if ($data->mch_id != $this->settings['mch_id']) {
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

        $this->eventsManager->fire('pay:afterPay', $this, $trade);

        return $gateway->success();
    }

    /**
     * 查询交易（扫码生成订单后可执行）
     *
     * @param string $outTradeNo
     * @param string $type
     * @return Collection|bool
     */
    public function find($outTradeNo, $type = 'wap')
    {
        $gateway = $this->getGateway();

        try {

            $order = ['out_trade_no' => $outTradeNo];

            $result = $gateway->find($order, $type);

        } catch (\Exception $e) {

            Log::error('Alipay Find Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 关闭交易（扫码生成订单后可执行）
     *
     * @param string $outTradeNo
     * @return bool
     */
    public function close($outTradeNo)
    {
        $gateway = $this->getGateway();

        try {

            $response = $gateway->close(['out_trade_no' => $outTradeNo]);

            $result = $response->result_code == 'SUCCESS';

        } catch (\Exception $e) {

            Log::error('Wxpay Close Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 取消交易
     *
     * @param string $outTradeNo
     * @return bool
     */
    public function cancel($outTradeNo)
    {
        return $this->close($outTradeNo);
    }

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     * @return Collection|bool
     */
    public function refund(RefundModel $refund)
    {
        $gateway = $this->getGateway();

        try {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findById($refund->trade_id);

            $response = $gateway->refund([
                'out_trade_no' => $trade->sn,
                'out_refund_no' => $refund->sn,
                'total_fee' => 100 * $trade->amount,
                'refund_fee' => 100 * $refund->amount,
            ]);

            $result = $response->result_code == 'SUCCESS';

        } catch (\Exception $e) {

            Log::error('Wxpay Refund Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 获取 Gateway
     *
     * @return WechatGateway
     */
    public function getGateway()
    {
        $config = $this->getDI()->get('config');

        $level = $config->env == ENV_DEV ? 'debug' : 'info';

        $payConfig = [
            'app_id' => $this->settings['app_id'],
            'mch_id' => $this->settings['mch_id'],
            'key' => $this->settings['key'],
            'notify_url' => $this->settings['notify_url'],
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
