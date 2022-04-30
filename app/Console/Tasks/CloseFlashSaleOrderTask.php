<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Order as OrderModel;
use App\Repos\FlashSale as FlashSaleRepo;
use App\Services\Logic\FlashSale\Queue as FlashSaleQueue;
use App\Services\Logic\FlashSale\UserOrderCache;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseFlashSaleOrderTask extends Task
{

    public function mainAction()
    {
        $orders = $this->findOrders();

        echo sprintf('pending orders: %s', $orders->count()) . PHP_EOL;

        if ($orders->count() == 0) return;

        echo '------ start close order task ------' . PHP_EOL;

        foreach ($orders as $order) {
            $this->incrFlashSaleStock($order->promotion_id);
            $this->pushFlashSaleQueue($order->promotion_id);
            $this->deleteUserOrderCache($order->owner_id, $order->promotion_id);
            $order->status = OrderModel::STATUS_CLOSED;
            $order->update();
        }

        echo '------ end close order task ------' . PHP_EOL;
    }

    protected function incrFlashSaleStock($saleId)
    {
        $flashSaleRepo = new FlashSaleRepo();

        $flashSale = $flashSaleRepo->findById($saleId);

        $flashSale->stock += 1;

        $flashSale->update();
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
