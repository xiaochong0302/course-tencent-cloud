<?php

namespace App\Http\Admin\Services;

use App\Models\Trade as TradeModel;
use App\Services\Payment\Wxpay as WxpayService;

class WxpayTest extends PaymentTest
{

    protected $channel = TradeModel::CHANNEL_WXPAY;

    public function scan(TradeModel $trade)
    {
        $wxpayService = new WxpayService();

        $qrcode = $wxpayService->scan($trade);

        return $qrcode ?: false;
    }

    public function status($tradeNo)
    {
        $wxpayService = new WxpayService();

        return $wxpayService->status($tradeNo);
    }

    public function cancel($tradeNo)
    {
        $wxpayService = new WxpayService();

        return $wxpayService->close($tradeNo);
    }

}
