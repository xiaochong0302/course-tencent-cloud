<?php

namespace App\Services\Logic\Notice\System;

use App\Models\Comment as CommentModel;
use App\Models\Notification as NotificationModel;
use App\Models\Question as QuestionModel;
use App\Services\Logic\Service as LogicService;

class QuestionCommented extends LogicService
{

    public function handle(QuestionModel $question, CommentModel $comment)
    {
        $commentContent = kg_substr($comment->content, 0, 32);

        $notification = new NotificationModel();

        $notification->sender_id = $comment->owner_id;
        $notification->receiver_id = $question->owner_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationModel::TYPE_QUESTION_COMMENTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->create();
    }

}
