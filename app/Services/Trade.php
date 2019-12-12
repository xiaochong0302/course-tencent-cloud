<?php

namespace App\Services;

use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;

class Trade extends Service
{

    /**
     * 创建交易
     *
     * @param string $sn
     * @param integer $channel
     * @return TradeModel $trade
     */
    public function createTrade($sn, $channel)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        $trade = new TradeModel();

        $trade->user_id = $order->user_id;
        $trade->order_sn = $order->sn;
        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;

        $trade->create();

        return $trade;
    }

    /**
     * 获取交易二维码
     *
     * @param $sn
     * @param $channel
     * @return bool|string|null
     */
    public function getQrCode($sn, $channel)
    {
        $trade = $this->createTrade($sn, $channel);

        $code = null;

        if ($channel == TradeModel::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $code = $alipay->getQrCode([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'subject' => $trade->subject,
            ]);
        } elseif ($channel == TradeModel::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $code = $wxpay->getQrCode([
                'out_trade_no' => $trade->sn,
                'total_fee' => 100 * $trade->amount,
                'body' => $trade->subject,
            ]);
        }

        return $code;
    }

    /**
     * 获取交易状态
     *
     * @param string $sn
     * @return string
     */
    public function getStatus($sn)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        return $trade->status;
    }

}
