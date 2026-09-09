<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Order as OrderModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseOrderTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey, 300);

        if (!$taskLockId) return;

        $orders = $this->findOrders();

        echo sprintf('pending orders: %s', $orders->count()) . PHP_EOL;

        if ($orders->count() == 0) return;

        echo '------ start close order task ------' . PHP_EOL;

        foreach ($orders as $order) {
            $this->closeOrder($order);
        }

        echo '------ end close order task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function closeOrder(OrderModel $order): void
    {
        try {

            $this->db->begin();

            $order->status = OrderModel::STATUS_CLOSED;

            $order->update();

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('order');

            $logger->error('Close Order Task Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'order' => $order,
                ]));
        }
    }

    /**
     * 查找待关闭订单
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    protected function findOrders(int $limit = 500)
    {
        $status = OrderModel::STATUS_PENDING;

        $time = time() - 12 * 3600;

        return OrderModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('create_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
