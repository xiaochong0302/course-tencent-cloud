<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Order as OrderModel;
use App\Services\Logic\FlashSale\Queue as FlashSaleQueue;
use App\Services\Logic\FlashSale\UserOrderCache;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseFlashSaleOrderTask extends Task
{

    public function mainAction()
    {
        $orders = $this->findOrders();

        if ($orders->count() == 0) return;

        foreach ($orders as $order) {
            $this->pushFlashSaleQueue($order->promotion_id);
            $this->deleteUserOrderCache($order->owner_id, $order->promotion_id);
            $order->status = OrderModel::STATUS_CLOSED;
            $order->update();
        }
    }

    protected function pushFlashSaleQueue($saleId)
    {
        $queue = new FlashSaleQueue();

        $queue->push($saleId);
    }

    protected function deleteUserOrderCache($userId, $saleId)
    {
        $cache = new UserOrderCache();

        $cache->delete($userId, $saleId);
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
        $type = OrderModel::PROMOTION_FLASH_SALE;
        $time = time() - 15 * 60;

        return OrderModel::query()
            ->where('status = :status:', ['status' => $status])
            ->andWhere('promotion_type = :type:', ['type' => $type])
            ->andWhere('create_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
