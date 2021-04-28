<?php

namespace App\Services\Logic\Notice\System;

use App\Models\Comment as CommentModel;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;

class CommentLiked extends LogicService
{

    public function handle(CommentModel $comment, UserModel $sender)
    {
        $comment->content = kg_substr($comment->content, 0, 32);

        $notification = new NotificationModel();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $comment->owner_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationModel::TYPE_COMMENT_LIKED;
        $notification->event_info = [
            'sender' => ['id' => $sender->id, 'name' => $sender->name],
            'comment' => ['id' => $comment->id, 'content' => $comment->content],
        ];

        $notification->create();
    }

}
