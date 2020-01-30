<?php

namespace App\Console\Tasks;

use App\Models\Trade as TradeModel;
use App\Services\Alipay as AlipayService;
use Phalcon\Cli\Task;

class CloseTradeTask extends Task
{

    public function mainAction()
    {
        $trades = $this->findTrades();

        if ($trades->count() == 0) {
            return;
        }

        foreach ($trades as $trade) {
            if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {
                $this->closeAlipayTrade($trade);
            } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {
                $this->closeWxpayTrade($trade);
            }
        }
    }

    /**
     * 关闭支付宝交易
     *
     * @param TradeModel $trade
     */
    protected function closeAlipayTrade($trade)
    {
        $service = new AlipayService();

        $alyOrder = $service->findOrder($trade->sn);

        if ($alyOrder) {
            if ($alyOrder->trade_status == 'WAIT_BUYER_PAY') {
                $service->closeOrder($trade->sn);
            }
        }

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 关闭微信交易
     *
     * @param TradeModel $trade
     */
    protected function closeWxpayTrade($trade)
    {
        $service = new WxpayService();

        $wxOrder = $service->findOrder($trade->sn);

        if ($wxOrder) {
            if ($wxOrder->trade_state == 'NOTPAY') {
                $service->closeOrder($trade->sn);
            }
        }

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 查找待关闭交易
     *
     * @param int $limit
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function findTrades($limit = 5)
    {
        $status = TradeModel::STATUS_PENDING;

        $createdAt = time() - 15 * 60;

        $trades = TradeModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('created_at < :created_at:', ['created_at' => $createdAt])
            ->limit($limit)
            ->execute();

        return $trades;
    }

}
