<?php

namespace App\Listeners;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use Phalcon\Events\Event;

class Payment extends Listener
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterPay(Event $event, $source, TradeModel $trade)
    {
        try {

            $this->db->begin();

            $trade->status = TradeModel::STATUS_FINISHED;

            if ($trade->update() === false) {
                throw new \RuntimeException('Update Trade Status Failed');
            }

            $orderRepo = new OrderRepo();

            $order = $orderRepo->findById($trade->order_id);

            $order->status = OrderModel::STATUS_FINISHED;

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
            $task->item_type = TaskModel::TYPE_ORDER;

            if ($task->create() === false) {
                throw new \RuntimeException('Create Order Process Task Failed');
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->logger->error('After Pay Event Exception {msg}',
                ['msg' => $e->getMessage()]
            );
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($trade),
        ]);
    }

}