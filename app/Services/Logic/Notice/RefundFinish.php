<?php

namespace App\Services\Logic\Notice;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Sms\Notice\RefundFinish as SmsRefundFinishNotice;
use App\Services\Wechat\Notice\RefundFinish as WechatRefundFinishNotice;

class RefundFinish extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $refundId = $task->item_info['refund']['id'];

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($refundId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($refund->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'order' => [
                'sn' => $refund->sn,
                'subject' => $refund->subject,
                'amount' => $refund->amount,
            ],
        ];

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($refund->owner_id);

        if ($subscribe && $subscribe->deleted == 0) {

            $notice = new WechatRefundFinishNotice();

            return $notice->handle($subscribe, $params);

        } else {

            $notice = new SmsRefundFinishNotice();

            return $notice->handle($user, $params);
        }
    }

    public function createTask(RefundModel $refund)
    {
        $task = new TaskModel();

        $itemInfo = [
            'refund' => ['id' => $refund->id],
        ];

        $task->item_id = $refund->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_ORDER_FINISH;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}
