<?php

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;

abstract class PaymentTest extends Service
{

    /**
     * @var string 支付平台
     */
    protected $channel;

    /**
     * 创建订单
     *
     * @return OrderModel
     */
    public function createOrder()
    {
        /**
         * @var object $authUser
         */
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
    public function createTrade(OrderModel $order)
    {
        $trade = new TradeModel();

        $trade->user_id = $order->user_id;
        $trade->order_id = $order->id;
        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $this->channel;

        $trade->create();

        return $trade;
    }

    /**
     * 交易状态
     *
     * @param string $tradeNo
     * @return string
     */
    abstract public function status($tradeNo);

    /**
     * 扫码下单
     *
     * @param TradeModel $trade
     * @return string|bool
     */
    abstract public function scan(TradeModel $trade);

    /**
     * 取消交易
     *
     * @param string $tradeNo
     * @return bool
     */
    abstract public function cancel($tradeNo);

}
