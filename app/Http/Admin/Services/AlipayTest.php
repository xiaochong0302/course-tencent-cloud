<?php

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Services\Pay\Alipay as AlipayService;

class AlipayTest extends PayTest
{

    protected $channel = TradeModel::CHANNEL_ALIPAY;

    public function scan(TradeModel $trade)
    {
        $alipayService = new AlipayService();

        $code = $alipayService->scan($trade);

        $codeUrl = null;

        if ($code) {
            $codeUrl = $this->url->get(
                ['for' => 'web.qrcode_img'],
                ['text' => urlencode($code)]
            );
        }

        return $codeUrl ?: false;
    }

    public function status($tradeNo)
    {
        $alipayService = new AlipayService();

        return $alipayService->status($tradeNo);
    }

    public function cancel($tradeNo)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($tradeNo);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($trade->order_id);

        $alipayService = new AlipayService();

        $response = $alipayService->cancel($trade->sn);

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
