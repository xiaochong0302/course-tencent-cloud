<?php

namespace App\Http\Web\Services;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Trade as TradeModel;
use App\Services\Frontend\Trade\TradeCreate as TradeCreateService;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;

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
        $qrcodeUrl = null;

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {
            $qrcodeUrl = $this->getAlipayQrCodeUrl($trade);
        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {
            $qrcodeUrl = $this->getWxpayQrCodeUrl($trade);
        }

        return $qrcodeUrl;
    }

    protected function getAlipayQrCodeUrl(TradeModel $trade)
    {
        $qrCodeUrl = null;

        $service = new AlipayService();

        $text = $service->scan($trade);

        if ($text) {
            $qrCodeUrl = $this->url->get(
                ['for' => 'web.qrcode_img'],
                ['text' => urlencode($text)]
            );
        }

        return $qrCodeUrl;
    }

    protected function getWxpayQrCodeUrl(TradeModel $trade)
    {
        $service = new WxpayService();

        return $service->scan($trade);
    }

}
