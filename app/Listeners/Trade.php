<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\FlashSale\UserOrderCache as FlashSaleLockCache;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Logger\Adapter\File as FileLogger;

class Trade extends Listener
{

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterPay(PhEvent $event, $source, TradeModel $trade)
    {
        try {

            $this->db->begin();

            $trade->status = TradeModel::STATUS_FINISHED;
            $trade->update();

            $orderRepo = new OrderRepo();
            $order = $orderRepo->findById($trade->order_id);

            $order->status = OrderModel::STATUS_DELIVERING;
            $order->update();

            $task = new TaskModel();

            $itemInfo = [
                'order' => ['id' => $order->id]
            ];

            $task->item_id = $order->id;
            $task->item_info = $itemInfo;
            $task->item_type = TaskModel::TYPE_DELIVER;
            $task->create();

            $this->db->commit();

            /**
             * 解除秒杀锁定
             */
            if ($order->promotion_type == OrderModel::PROMOTION_FLASH_SALE) {
                $cache = new FlashSaleLockCache();
                $cache->delete($order->owner_id, $order->promotion_id);
            }

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->logger->error('After Pay Event Error ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}