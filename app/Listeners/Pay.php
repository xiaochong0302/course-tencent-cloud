<?php

namespace App\Listeners;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Logger\Adapter\File as FileLogger;

class Pay extends Listener
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

            if ($trade->update() === false) {
                throw new \RuntimeException('Update Trade Status Failed');
            }

            $orderRepo = new OrderRepo();

            $order = $orderRepo->findById($trade->order_id);

            $order->status = OrderModel::STATUS_DELIVERING;

            if ($order->update() === false) {
                throw new \RuntimeException('Update Order Status Failed');
            }

            $task = new TaskModel();

            $itemInfo = [
                'order' => [
                    'id' => $order->id,
                    'item_id' => $order->item_id,
                    'item_type' => $order->item_type,
                ]
            ];

            $task->item_id = $order->id;
            $task->item_info = $itemInfo;
            $task->item_type = TaskModel::TYPE_DELIVER;

            if ($task->create() === false) {
                throw new \RuntimeException('Create Order Process Task Failed');
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->logger->error('After Pay Event Error ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $this->logger->debug('After Pay Event Info ' . kg_json_encode([
                    'event' => $event->getType(),
                    'source' => get_class($source),
                    'data' => kg_json_encode($trade),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}