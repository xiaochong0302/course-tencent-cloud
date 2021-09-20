<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Pay;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use App\Services\Pay as PayService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Yansongda\Pay\Gateways\Wechat;
use Yansongda\Pay\Log;
use Yansongda\Supports\Collection;

class Wxpay extends PayService
{

    /**
     * @var Wechat
     */
    protected $gateway;

    public function __construct($gateway = null)
    {
        $gateway = $gateway instanceof WxpayGateway ? $gateway : new WxpayGateway();

        $this->gateway = $gateway->getInstance();
    }

    /**
     * 扫码下单
     *
     * @param TradeModel $trade
     * @return bool|string
     */
    public function scan(TradeModel $trade)
    {
        try {

            $response = $this->gateway->scan([
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
     * app支付
     *
     * @param TradeModel $trade
     * @return Response|bool
     */
    public function app(TradeModel $trade)
    {
        try {

            $result = $this->gateway->app([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Log::error('Wxpay App Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * wap支付
     *
     * @param TradeModel $trade
     * @return RedirectResponse|bool
     */
    public function wap(TradeModel $trade)
    {
        try {

            $result = $this->gateway->wap([
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
     * 公众号支付
     *
     * @param TradeModel $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mp(TradeModel $trade, $openId)
    {
        try {

            $result = $this->gateway->mp([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
                'openid' => $openId,
            ]);

        } catch (\Exception $e) {

            Log::error('Wxpay MP Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 小程序支付
     *
     * @param TradeModel $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mini(TradeModel $trade, $openId)
    {
        try {

            $result = $this->gateway->miniapp([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
                'openid' => $openId,
            ]);

        } catch (\Exception $e) {

            Log::error('Wxpay Mini Exception', [
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
     * @return Response|bool
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

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data->out_trade_no);

        if (!$trade) return false;

        /**
         * 注意浮点数精度丢失问题
         */
        $amount = intval(strval(100 * $trade->amount));

        if ($data->total_fee != $amount) {
            return false;
        }

        if ($trade->status == TradeModel::STATUS_FINISHED) {
            return $this->gateway->success();
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->transaction_id;

        $this->eventsManager->fire('Trade:afterPay', $this, $trade);

        $trade = $tradeRepo->findById($trade->id);

        if ($trade->status == TradeModel::STATUS_FINISHED) {
            return $this->gateway->success();
        }

        return false;
    }

    /**
     * 查询交易（扫码生成订单后可执行）
     *
     * @param string $tradeNo
     * @param string $type
     * @return Collection|bool
     */
    public function find($tradeNo, $type = 'wap')
    {
        try {

            $order = ['out_trade_no' => $tradeNo];

            $result = $this->gateway->find($order, $type);

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
     * @param string $tradeNo
     * @return bool
     */
    public function close($tradeNo)
    {
        try {

            $response = $this->gateway->close(['out_trade_no' => $tradeNo]);

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
     * @param string $tradeNo
     * @return bool
     */
    public function cancel($tradeNo)
    {
        return $this->close($tradeNo);
    }

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     * @return Collection|bool
     */
    public function refund(RefundModel $refund)
    {
        try {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findById($refund->trade_id);

            $response = $this->gateway->refund([
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

}
