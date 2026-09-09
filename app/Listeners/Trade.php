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
use Phalcon\Events\Event as PhEvent;

class Trade extends Listener
{

    public function afterSuccess(PhEvent $event, object $source, TradeModel $trade): void
    {
        try {

            $this->db->begin();

            $trade->status = TradeModel::STATUS_FINISHED;
            $trade->update();

            $orderRepo = new OrderRepo();
            $order = $orderRepo->findById($trade->order_id);

            $order->channel = $trade->channel;
            $order->status = OrderModel::STATUS_DELIVERING;
            $order->update();

            $task = new TaskModel();

            $task->item_id = $order->id;
            $task->item_type = TaskModel::TYPE_DELIVER;
            $task->priority = TaskModel::PRIORITY_HIGH;
            $task->create();

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('trade');

            $logger->error('Trade After Success Event Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trade' => $trade,
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
