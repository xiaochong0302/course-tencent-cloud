<?php

namespace App\Console\Tasks;

use App\Models\Trade as TradeModel;
use App\Services\Payment\Alipay as AlipayService;
use App\Services\Payment\Wxpay as WxpayService;
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
        $alipay = new AlipayService();

        $success = $alipay->close($trade->sn);

        if ($success) {
            $trade->status = TradeModel::STATUS_CLOSED;
            $trade->update();
        }
    }

    /**
     * 关闭微信交易
     *
     * @param TradeModel $trade
     */
    protected function closeWxpayTrade($trade)
    {
        $wxpay = new WxpayService();

        $success = $wxpay->close($trade->sn);

        if ($success) {
            $trade->status = TradeModel::STATUS_CLOSED;
            $trade->update();
        }
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
