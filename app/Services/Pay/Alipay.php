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
use Symfony\Component\HttpFoundation\Response;
use Yansongda\Pay\Log;
use Yansongda\Supports\Collection;

class Alipay extends PayService
{

    /**
     * @var \Yansongda\Pay\Gateways\Alipay
     */
    protected $gateway;

    public function __construct($gateway = null)
    {
        $gateway = $gateway instanceof AlipayGateway ? $gateway : new AlipayGateway();

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
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);

            $result = $response->qr_code ?? false;

        } catch (\Exception $e) {

            Log::error('Alipay Qrcode Exception', [
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
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);

        } catch (\Exception $e) {

            Log::error('Alipay app Exception', [
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
     * @return Response|bool
     */
    public function wap(TradeModel $trade)
    {
        try {

            $result = $this->gateway->wap([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
                'http_method' => 'GET',
            ]);

        } catch (\Exception $e) {

            Log::error('Alipay Wap Exception', [
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
     * @param string $buyerId
     * @return Collection|bool
     */
    public function mini(TradeModel $trade, $buyerId)
    {
        try {

            $result = $this->gateway->mini([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
                'buyer_id' => $buyerId,
            ]);

        } catch (\Exception $e) {

            Log::error('Alipay Mini Exception', [
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

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data->out_trade_no);

        if (!$trade) return false;

        if ($data->total_amount != $trade->amount) {
            return false;
        }

        if ($trade->status == TradeModel::STATUS_FINISHED) {
            return $this->gateway->success();
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data->trade_no;

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

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Log::error('Alipay Close Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 撤销交易（未生成订单也可执行）
     *
     * @param string $tradeNo
     * @return bool
     */
    public function cancel($tradeNo)
    {
        try {

            $response = $this->gateway->cancel(['out_trade_no' => $tradeNo]);

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Log::error('Alipay Cancel Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     * @return bool
     */
    public function refund(RefundModel $refund)
    {
        try {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findById($refund->trade_id);

            $response = $this->gateway->refund([
                'out_trade_no' => $trade->sn,
                'out_request_no' => $refund->sn,
                'refund_amount' => $refund->amount,
            ]);

            $result = $response->code == '10000';

        } catch (\Exception $e) {

            Log::error('Alipay Refund Order Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

}
