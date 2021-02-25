<?php

namespace App\Services\DingTalk\Notice;

use App\Models\ImMessage as ImMessageModel;
use App\Models\Task as TaskModel;
use App\Repos\ImMessage as ImMessageRepo;
use App\Repos\User as UserRepo;
use App\Services\DingTalkNotice;

class CustomService extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $messageRepo = new ImMessageRepo();

        $message = $messageRepo->findById($task->item_id);

        $userRepo = new UserRepo();

        $sender = $userRepo->findById($message->sender_id);

        $content = kg_ph_replace("{user.name} 通过在线客服给你发送了消息：{message.content}", [
            'user.name' => $sender->name,
            'message.content' => $message->content,
        ]);

        $this->atCustomService($content);
    }

    public function createTask(ImMessageModel $message)
    {
        if (!$this->enabled) return;

        $keyName = "dingtalk_custom_service_notice:{$message->sender_id}";

        $cache = $this->getCache();

        $content = $cache->get($keyName);

        if ($content) return;

        $cache->save($keyName, 1, 3600);

        $task = new TaskModel();

        $itemInfo = [
            'im_message' => ['id' => $message->id],
        ];

        $task->item_id = $message->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_CUSTOM_SERVICE;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}