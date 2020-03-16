<?php

namespace App\Console\Tasks;

use App\Models\Order as OrderModel;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseOrderTask extends Task
{

    public function mainAction()
    {
        $orders = $this->findOrders();

        if ($orders->count() == 0) {
            return;
        }

        foreach ($orders as $order) {
            $order->status = OrderModel::STATUS_CLOSED;
            $order->update();
        }
    }

    /**
     * 查找待关闭订单
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    protected function findOrders($limit = 1000)
    {
        $status = OrderModel::STATUS_PENDING;

        $createdAt = time() - 12 * 3600;

        $orders = OrderModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('created_at < :created_at:', ['created_at' => $createdAt])
            ->limit($limit)
            ->execute();

        return $orders;
    }

}
