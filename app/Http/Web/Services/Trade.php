<?php

namespace App\Http\Web\Services;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Trade as TradeModel;
use App\Services\Frontend\Trade\TradeCreate as TradeCreateService;
use App\Services\Pay\Alipay as AlipayService;

class Trade extends Service
{

    public function create()
    {
        $this->db->begin();

        $service = new TradeCreateService();

        $trade = $service->handle();

        $qrCodeUrl = $this->getQrCodeUrl($trade);

        if ($trade && $qrCodeUrl) {

            $this->db->commit();

            return [
                'sn' => $trade->sn,
                'channel' => $trade->channel,
                'qrcode_url' => $qrCodeUrl,
            ];

        } else {

            $this->db->rollback();

            throw new BadRequestException('trade.create_failed');
        }
    }

    protected function getQrCodeUrl(TradeModel $trade)
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

}
