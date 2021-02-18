<?php

namespace App\Services\DingTalk\Notice;

use App\Models\ImMessage as ImMessageModel;
use App\Repos\User as UserRepo;
use App\Services\DingTalkNotice;

class CustomService extends DingTalkNotice
{

    public function handle(ImMessageModel $message)
    {
        $keyName = "dingtalk_cs_notice:{$message->sender_id}";

        $cache = $this->getCache();

        $content = $cache->get($keyName);

        if ($content) return;

        $cache->save($keyName, 1, 3600);

        $userRepo = new UserRepo();

        $sender = $userRepo->findById($message->sender_id);

        $content = kg_ph_replace("{user} 通过在线客服给你发送了消息：{message}", [
            'user' => $sender->name,
            'message' => $message->content,
        ]);

        $this->atCustomService($content);
    }

}