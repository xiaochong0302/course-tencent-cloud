<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\KgPayment as KgPaymentModel;
use App\Models\Refund as RefundModel;
use App\Models\RefundStatus as RefundStatusModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class RefundTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey, 300);

        if (!$taskLockId) return;

        $refunds = $this->findRefunds();

        echo sprintf('pending refunds: %s', $refunds->count()) . PHP_EOL;

        if ($refunds->count() == 0) return;

        echo '------ start refund task ------' . PHP_EOL;

        foreach ($refunds as $refund) {
            $this->handleRefund($refund);
        }

        echo '------ end refund task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleRefund(RefundModel $refund): void
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($refund->trade_id);

        if ($trade->status == TradeModel::STATUS_REFUNDED) return;

        $isRemoteRefundSuccess = $this->isRemoteRefundSuccess($refund, $trade);

        if ($this->isRefundTimeout($refund) && !$isRemoteRefundSuccess) {

            $this->getPhEventsManager()->fire('Refund:afterFail', $this, $refund);

            $logger = $this->getLogger('refund');

            $logger->info("Refund {$refund->id}: marked as failed due to timeout");

            return;
        }

        $tradeFinished = $trade->status == TradeModel::STATUS_FINISHED;

        if ($tradeFinished && $isRemoteRefundSuccess) {
            $this->getPhEventsManager()->fire('Refund:afterSuccess', $this, $refund);
        }
    }

    protected function isRemoteRefundSuccess(RefundModel $refund, TradeModel $trade): bool
    {
        $result = false;

        if ($trade->channel == KgPaymentModel::CHANNEL_ALIPAY) {

            $service = new AlipayService();

            $result = $service->isRefundSuccess($refund);

        } elseif ($trade->channel == KgPaymentModel::CHANNEL_WXPAY) {

            $service = new WxpayService();

            $result = $service->isRefundSuccess($refund);
        }

        return $result;
    }

    protected function isRefundTimeout(RefundModel $refund): bool
    {
        $row = $this->findApprovedRefundStatus($refund->id);

        $result = false;

        if ($row) {
            $result = $row->create_time + 24 * 3600 < time();
        }

        return $result;
    }

    /**
     * @param int $refundId
     * @return RefundStatusModel|Row|null
     */
    protected function findApprovedRefundStatus(int $refundId)
    {
        return RefundStatusModel::findFirst([
            'conditions' => 'refund_id = :refund_id: AND status = :status:',
            'bind' => [
                'refund_id' => $refundId,
                'status' => RefundModel::STATUS_APPROVED,
            ],
            'order' => 'id DESC',
        ]);
    }

    /**
     * 查找待处理退款申请
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    protected function findRefunds(int $limit = 100)
    {
        $status = RefundModel::STATUS_APPROVED;

        return RefundModel::query()
            ->where('status = :status:', ['status' => $status])
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
