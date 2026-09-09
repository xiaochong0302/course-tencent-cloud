<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\KgPayment as KgPaymentModel;
use App\Models\Trade as TradeModel;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseTradeTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey, 300);

        if (!$taskLockId) return;

        $trades = $this->findTrades();

        echo sprintf('pending trades: %s', $trades->count()) . PHP_EOL;

        if ($trades->count() == 0) return;

        echo '------ start close trade ------' . PHP_EOL;

        foreach ($trades as $trade) {
            try {
                if ($trade->channel == KgPaymentModel::CHANNEL_ALIPAY) {
                    $this->handleAlipayTrade($trade);
                } elseif ($trade->channel == KgPaymentModel::CHANNEL_WXPAY) {
                    $this->handleWxpayTrade($trade);
                }
            } catch (\Exception $e) {
                $logger = $this->getLogger('trade');
                $logger->error('Close Trade Task Exception: ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'trade' => $trade,
                    ]));
            }
        }

        echo '------ end close trade ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    /**
     * 处理支付宝交易
     *
     * @param TradeModel $trade
     */
    protected function handleAlipayTrade(TradeModel $trade): void
    {
        $alipay = new AlipayService();

        /**
         * 异步通知接收异常，补救漏网
         */
        if ($alipay->isTradeSuccess($trade)) {
            $this->getPhEventsManager()->fire('Trade:afterSuccess', $this, $trade);
            return;
        }

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 处理微信交易
     *
     * @param TradeModel $trade
     */
    protected function handleWxpayTrade(TradeModel $trade): void
    {
        $wxpay = new WxpayService();

        /**
         * 异步通知接收异常，补救漏网
         */
        if ($wxpay->isTradeSuccess($trade)) {
            $this->getPhEventsManager()->fire('Trade:afterSuccess', $this, $trade);
            return;
        }

        $trade->status = TradeModel::STATUS_CLOSED;

        $trade->update();
    }

    /**
     * 查找待关闭交易
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    protected function findTrades(int $limit = 100)
    {
        $status = TradeModel::STATUS_PENDING;

        $lifetime = kg_config('trade.lifetime', 15 * 60);

        $createTime = time() - $lifetime;

        return TradeModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('create_time < :create_time:', ['create_time' => $createTime])
            ->limit($limit)
            ->execute();
    }

}
