<?php

namespace App\Services\Frontend\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxPayService;
use App\Validators\Trade as TradeValidator;

class TradeCreate extends Service
{

    use OrderTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $validator = new TradeValidator();

        $channel = $validator->checkChannel($post['channel']);

        try {

            $this->db->begin();

            $trade = new TradeModel();

            $trade->subject = $order->subject;
            $trade->amount = $order->amount;
            $trade->channel = $channel;
            $trade->order_id = $order->id;
            $trade->user_id = $user->id;

            $trade->create();

            $qrCodeUrl = $this->getQrCodeUrl($trade);

            $this->db->commit();

            return [
                'trade_sn' => $trade->sn,
                'code_url' => $qrCodeUrl,
            ];

        } catch (\Exception $e) {

            $this->db->rollback();

            throw new \RuntimeException('trade.create_failed');
        }
    }

    protected function getQrCodeUrl(TradeModel $trade)
    {
        $qrCodeUrl = null;

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {

            $alipayService = new AlipayService();

            $text = $alipayService->scan($trade);

            if ($text) {
                $qrCodeUrl = $this->url->get(
                    ['for' => 'web.qrcode_img'],
                    ['text' => urlencode($text)]
                );
            }

        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {

            $wxpayService = new WxPayService();

            $qrCodeUrl = $wxpayService->scan($trade);
        }

        return $qrCodeUrl;
    }

}
