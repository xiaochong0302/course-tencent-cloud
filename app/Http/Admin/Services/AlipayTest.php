<?php

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Services\Alipay as AlipayService;

class AlipayTest extends PaymentTest
{

    /**
     * 获取测试二维码
     *
     * @param TradeModel $trade
     * @return mixed
     */
    public function getTestQrCode($trade)
    {
        $outOrder = [
            'out_trade_no' => $trade->sn,
            'total_amount' => $trade->amount,
            'subject' => $trade->subject,
        ];

        $alipayService = new AlipayService();

        $qrcode = $alipayService->getQrCode($outOrder);

        $result = $qrcode ?: false;

        return $result;
    }

    /**
     * 取消测试订单
     *
     * @param string $sn
     */
    public function cancelTestOrder($sn)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($trade->order_id);

        $alipayService = new AlipayService();

        $response = $alipayService->cancelOrder($trade->sn);

        if ($response) {
            $trade->status = TradeModel::STATUS_CLOSED;
            $trade->update();
            if ($order->status != OrderModel::STATUS_PENDING) {
                $order->status = OrderModel::STATUS_PENDING;
                $order->update();
            }
        }
    }

}
