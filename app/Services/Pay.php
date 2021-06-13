<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

abstract class Pay extends Service
{

    /**
     * 交易状态
     *
     * @param string $tradeNo
     * @return int
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
     * wap下单
     *
     * @param TradeModel $trade
     */
    abstract public function wap(TradeModel $trade);

    /**
     * 异步通知
     */
    abstract public function notify();

    /**
     * 查找交易
     *
     * @param string $tradeNo
     * @param string $type
     */
    abstract public function find($tradeNo, $type);

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
