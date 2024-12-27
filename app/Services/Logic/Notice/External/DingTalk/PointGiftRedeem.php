<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\DingTalk;

use App\Models\PointGift as PointGiftModel;
use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\PointGiftRedeem as PointGiftRedeemRepo;
use App\Services\DingTalkNotice;

class PointGiftRedeem extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $redeemRepo = new PointGiftRedeemRepo();

        $redeem = $redeemRepo->findById($task->item_id);

        $content = kg_ph_replace("{user.name} 兑换了商品 {gift.name}，不要忘记发货哦！", [
            'user.name' => $redeem->user_name,
            'gift.name' => $redeem->gift_name,
        ]);

        $this->atCustomService($content);
    }

    public function createTask(PointGiftRedeemModel $redeem)
    {
        if (!$this->enabled) return;

        if ($redeem->gift_type != PointGiftModel::TYPE_GOODS) return;

        $task = new TaskModel();

        $task->item_id = $redeem->id;
        $task->item_type = TaskModel::TYPE_STAFF_NOTICE_POINT_GIFT_REDEEM;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}
