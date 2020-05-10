<?php

namespace App\Console\Tasks;

use App\Models\Trade as TradeModel;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

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
                $this->handleAlipayTrade($trade);
            } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {
                $this->handleWxpayTrade($trade);
            }
        }
    }

    /**
     * 处理支付宝交易
     *
     * @param TradeModel $trade
     */
    protected function handleAlipayTrade($trade)
    {
        $allowClosed = true;

        $alipay = new AlipayService();

        $alipayTrade = $alipay->find($trade->sn);

        if ($alipayTrade) {
            /**
             * 异步通知接收异常，补救漏网
             */
            if ($alipayTrade->trade_status == 'TRADE_SUCCESS') {
                $this->eventsManager->fire('pay:afterPay', $this, $trade);
                $allowClosed = false;
            } elseif ($alipayTrade->trade_status == 'WAIT_BUYER_PAY') {
                $alipay->close($trade->sn);
            }
        }

        if (!$allowClosed) return;

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 处理微信交易
     *
     * @param TradeModel $trade
     */
    protected function handleWxpayTrade($trade)
    {
        $allowClosed = true;

        $wxpay = new WxpayService();

        $wxpayTrade = $wxpay->find($trade->sn);

        if ($wxpayTrade) {
            /**
             * 异步通知接收异常，补救漏网
             */
            if ($wxpayTrade->trade_state == 'SUCCESS') {
                $this->eventsManager->fire('pay:afterPay', $this, $trade);
                $allowClosed = false;
            } elseif ($wxpayTrade->trade_state == 'NOTPAY') {
                $wxpay->close($trade->sn);
            }
        }

        if (!$allowClosed) return;

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 查找待关闭交易
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    protected function findTrades($limit = 5)
    {
        $status = TradeModel::STATUS_PENDING;

        $createTime = time() - 15 * 60;

        return TradeModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('create_time < :create_time:', ['create_time' => $createTime])
            ->limit($limit)
            ->execute();
    }

}
