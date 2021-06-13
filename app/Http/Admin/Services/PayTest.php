<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Services\Auth\Admin as AdminAuth;

abstract class PayTest extends Service
{

    /**
     * @var string 支付平台
     */
    protected $channel;

    /**
     * 创建支付宝订单
     *
     * @return OrderModel
     */
    public function createAlipayOrder()
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        $authUser = $auth->getAuthInfo();

        $order = new OrderModel();

        $order->subject = '测试 - 支付测试0.01元';
        $order->amount = 0.01;
        $order->owner_id = $authUser['id'];
        $order->item_type = OrderModel::ITEM_TEST;

        $order->create();

        return $order;
    }

    /**
     * 创建微信订单
     *
     * @return OrderModel
     */
    public function createWxpayOrder()
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        $authUser = $auth->getAuthInfo();

        $config = $this->getConfig();

        $order = new OrderModel();

        /**
         * 微信沙箱环境金额不能自定义，只能是固定测试用例值（SB的不行）
         */
        if ($config->get('env') == ENV_DEV) {
            $order->subject = '测试 - 支付测试3.01元';
            $order->amount = 3.01;
        } else {
            $order->subject = '测试 - 支付测试0.01元';
            $order->amount = 0.01;
        }

        $order->owner_id = $authUser['id'];
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

        $trade->owner_id = $order->owner_id;
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

}
