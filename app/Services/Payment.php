<?php

namespace App\Services;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

abstract class Payment extends Service
{

    /**
     * 交易状态
     *
     * @param string $tradeNo
     * @return string
     */
    public function status($tradeNo)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($tradeNo);

        return $trade->status;
    }

    /**
     * 扫码下单
     *
     * @param TradeModel $trade
     */
    abstract public function scan(TradeModel $trade);

    /**
     * 异步通知
     */
    abstract public function notify();

    /**
     * 查找交易
     *
     * @param string $tradeNo
     */
    abstract public function find($tradeNo);

    /**
     * 关闭交易
     *
     * @param string $tradeNo
     */
    abstract public function close($tradeNo);

    /**
     * 取消交易
     *
     * @param string $tradeNo
     */
    abstract public function cancel($tradeNo);

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     */
    abstract public function refund(RefundModel $refund);

}
