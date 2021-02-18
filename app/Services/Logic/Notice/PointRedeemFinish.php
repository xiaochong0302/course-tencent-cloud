<?php

namespace App\Services\Logic\Notice;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Sms\Notice\OrderFinish as SmsOrderFinishNotice;
use App\Services\Wechat\Notice\OrderFinish as WechatOrderFinishNotice;

class PointRedeemFinish extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $orderId = $task->item_info['order']['id'];

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
                'create_time' => $order->create_time,
                'update_time' => $order->update_time,
            ],
        ];

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($order->owner_id);

        if ($subscribe && $subscribe->deleted == 0) {

            $notice = new WechatOrderFinishNotice();

            return $notice->handle($subscribe, $params);

        } else {

            $notice = new SmsOrderFinishNotice();

            return $notice->handle($user, $params);
        }
    }

    public function createTask(OrderModel $order)
    {
        $task = new TaskModel();

        $itemInfo = [
            'order' => ['id' => $order->id],
        ];

        $task->item_id = $order->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_ORDER_FINISH;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}
