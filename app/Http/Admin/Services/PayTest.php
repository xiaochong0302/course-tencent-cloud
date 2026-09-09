<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;

abstract class PayTest extends Service
{

    /**
     * @var int 支付平台
     */
    protected int $channel;

    /**
     * @var string 支付场景
     */
    protected string $scene;

    public function handle(): array
    {
        try {

            $this->db->begin();

            $order = $this->createOrder();
            $trade = $this->createTrade($order);
            $qrcode = $this->getQrCode($trade);

            $this->db->commit();

            return [
                'sn' => $trade->sn,
                'qrcode' => $qrcode,
            ];

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('trade');

            $logger->error('PayTest Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

    protected function createOrder(): OrderModel
    {
        $authUser = $this->getLoginUser();

        $order = new OrderModel();

        $order->subject = '支付测试 - 支付测试0.01元';
        $order->amount = 0.01;
        $order->owner_id = $authUser->id;
        $order->item_type = KgProductModel::ITEM_PAY_TEST;

        $order->create();

        return $order;
    }

    protected function createTrade(OrderModel $order): TradeModel
    {
        $trade = new TradeModel();

        $trade->owner_id = $order->owner_id;
        $trade->order_id = $order->id;
        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $this->channel;
        $trade->scene = $this->scene;

        $trade->create();

        return $trade;
    }

    /**
     * 获取二维码
     *
     * @param TradeModel $trade
     * @return string|null
     */
    abstract protected function getQrCode(TradeModel $trade): ?string;

    /**
     * 交易状态
     *
     * @param string $tradeNo
     * @return int
     */
    abstract public function status(string $tradeNo): int;

}
