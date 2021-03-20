<?php

namespace App\Console\Tasks;

use App\Models\Order as OrderModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseOrderTask extends Task
{

    public function mainAction()
    {
        $orders = $this->findOrders();

        if ($orders->count() == 0) return;

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
        $time = time() - 12 * 3600;
        $type = 0;

        return OrderModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('promotion_type = :type:', ['type' => $type])
            ->andWhere('create_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
