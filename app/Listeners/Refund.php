<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use Phalcon\Events\Event as PhEvent;

class Refund extends Listener
{

    public function afterSuccess(PhEvent $event, object $source, RefundModel $refund): void
    {
        $task = new TaskModel();

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND_AFTER_SUCCESS;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function afterFail(PhEvent $event, object $source, RefundModel $refund): void
    {
        $task = new TaskModel();

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND_AFTER_FAIL;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}
