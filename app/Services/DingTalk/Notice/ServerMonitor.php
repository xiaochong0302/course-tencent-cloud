<?php

namespace App\Services\DingTalk\Notice;

use App\Models\Task as TaskModel;
use App\Services\DingTalkNotice;

class ServerMonitor extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $notice = new DingTalkNotice();

        $content = $task->item_info['content'];

        $notice->atTechSupport($content);
    }

    public function createTask($content)
    {
        if (!$this->enabled) return;

        $task = new TaskModel();

        $itemInfo = ['content' => $content];

        $task->item_id = time();
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_SERVER_MONITOR;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

}