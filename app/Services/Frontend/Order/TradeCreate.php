<?php

namespace App\Services\Frontend\Order;

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

            $qrCode = $this->getQrCode($trade);

            $this->db->commit();

            return $qrCode;

        } catch (\Exception $e) {

            $this->db->rollback();

            return false;
        }
    }

    protected function getQrCode(TradeModel $trade)
    {
        $qrCode = null;

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {

            $alipayService = new AlipayService();

            $qrCode = $alipayService->scan($trade);

        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {

            $wxpayService = new WxPayService();

            $qrCode = $wxpayService->scan($trade);
        }

        return $qrCode;
    }

}
