<?php

namespace App\Services\Logic\Notice\System;

use App\Models\Answer as AnswerModel;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\Service as LogicService;

class AnswerRejected extends LogicService
{

    public function handle(AnswerModel $answer, UserModel $sender, $reason)
    {
        $answerSummary = kg_substr($answer->summary, 0, 32);

        $question = $this->findQuestion($answer->question_id);

        $notification = new NotificationModel();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $answer->owner_id;
        $notification->event_id = $answer->id;
        $notification->event_type = NotificationModel::TYPE_ANSWER_REJECTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
            'reason' => $reason,
        ];

        $notification->create();
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        return $questionRepo->findById($id);
    }

}
