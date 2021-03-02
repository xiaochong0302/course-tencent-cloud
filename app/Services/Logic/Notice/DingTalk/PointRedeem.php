<?php

namespace App\Services\Logic\Notice\DingTalk;

use App\Models\PointGift as PointGiftModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\PointRedeem as PointRedeemRepo;
use App\Services\DingTalkNotice;

class PointRedeem extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $redeemRepo = new PointRedeemRepo();

        $redeem = $redeemRepo->findById($task->item_id);

        $content = kg_ph_replace("{user.name} 兑换了商品 {gift.name}，不要忘记发货哦！", [
            'user.name' => $redeem->user_name,
            'gift.name' => $redeem->gift_name,
        ]);

        $this->atCustomService($content);
    }

    public function createTask(PointRedeemModel $redeem)
    {
        if (!$this->enabled) return;

        if ($redeem->gift_type != PointGiftModel::TYPE_GOODS) return;

        $task = new TaskModel();

        $itemInfo = [
            'point_redeem' => ['id' => $redeem->id],
        ];

        $task->item_id = $redeem->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_POINT_REDEEM;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}