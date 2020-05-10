<?php

namespace App\Services\Frontend\Pay;

use App\Models\Trade as TradeModel;
use App\Services\Frontend\Service;
use App\Services\Pay\Alipay as AlipayService;

class Alipay extends Service
{

    public function scan(TradeModel $trade)
    {
        $qrCodeUrl = null;

        $alipayService = new AlipayService();

        $text = $alipayService->scan($trade);

        if ($text) {
            $qrCodeUrl = $this->url->get(
                ['for' => 'web.qrcode_img'],
                ['text' => urlencode($text)]
            );
        }

        return $qrCodeUrl;
    }

    public function wap(TradeModel $trade)
    {

    }

}
