<?php

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

abstract class PaymentTest extends Service
{

    /**
     * 创建测试订单
     *
     * @return OrderModel
     */
    public function createTestOrder()
    {
        $authUser = $this->getDI()->get('auth')->getAuthInfo();

        $order = new OrderModel();

        $order->subject = '测试 - 支付测试0.01元';
        $order->amount = 0.01;
        $order->user_id = $authUser->id;
        $order->item_type = OrderModel::ITEM_TEST;

        $order->create();

        return $order;
    }

    /**
     * 创建交易
     *
     * @param OrderModel $order
     * @return TradeModel $trade
     */
    public function createTestTrade($order)
    {
        $trade = new TradeModel();

        $trade->user_id = $order->user_id;
        $trade->order_id = $order->id;
        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = TradeModel::CHANNEL_ALIPAY;

        $trade->create();

        return $trade;
    }

    /**
     * 获取订单状态
     *
     * @param string $sn
     * @return string
     */
    public function getTestStatus($sn)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        return $trade->status;
    }

    /**
     * 获取测试二维码
     *
     * @param TradeModel $trade
     * @return mixed
     */
    abstract public function getTestQrCode($trade);

    /**
     * 取消测试订单
     *
     * @param string $sn
     */
    abstract public function cancelTestOrder($sn);

}
