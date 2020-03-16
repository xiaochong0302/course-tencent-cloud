<?php

namespace App\Services\Frontend;

use App\Models\Trade as TradeModel;
use App\Services\Alipay as AlipayService;
use App\Services\Wechat as WxPayService;
use App\Validators\Trade as TradeValidator;

class OrderTrade extends Service
{

    use OrderTrait;

    /**
     * @param string $sn
     * @return mixed
     */
    public function createTrade($sn)
    {
        $post = $this->request->getPost();

        $order = $this->checkOrder($sn);

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

    /**
     * @param TradeModel $trade
     * @return mixed
     */
    protected function getQrCode(TradeModel $trade)
    {
        $qrCode = null;

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {

            $alipayService = new AlipayService();

            $qrCode = $alipayService->getQrCode([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);

        } elseif ($trade->channel == TradeModel::CHANNEL_WECHAT) {

            $wechatService = new WxPayService();

            $qrCode = $wechatService->getQrCode([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);
        }

        return $qrCode;
    }

}
