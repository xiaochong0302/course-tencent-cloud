<?php

namespace App\Http\Admin\Services;

use App\Models\Trade as TradeModel;

class WxpayTest extends PaymentTest
{

    /**
     * 获取测试二维码
     *
     * @param TradeModel $trade
     * @return mixed
     */
    public function getTestQrcode($trade)
    {
        $outOrder = [
            'out_trade_no' => $trade->sn,
            'total_fee' => 100 * $trade->amount,
            'body' => $trade->subject,
        ];

        $wxpayService = new WxpayService();
        $qrcode = $wxpayService->qrcode($outOrder);
        $result = $qrcode ?: false;

        return $result;
    }

    /**
     * 取消测试订单
     *
     * @param string $sn
     */
    public function cancelTestOrder($sn)
    {

    }

}
