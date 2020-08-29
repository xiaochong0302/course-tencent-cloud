<?php

namespace App\Http\Admin\Services;

use App\Models\Trade as TradeModel;
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
                ['for' => 'desktop.qrcode'],
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

}
