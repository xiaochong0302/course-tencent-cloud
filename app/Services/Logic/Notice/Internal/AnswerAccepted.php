<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\Internal;

use App\Models\Answer as AnswerModel;
use App\Models\Notification as NotificationModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\Service as LogicService;

class AnswerAccepted extends LogicService
{

    public function handle(AnswerModel $answer, UserModel $sender)
    {
        $answerSummary = kg_substr($answer->summary, 0, 36);

        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($answer->question_id);

        $notification = new NotificationModel();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $answer->owner_id;
        $notification->event_id = $answer->id;
        $notification->event_type = NotificationModel::TYPE_ANSWER_ACCEPTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
        ];

        $notification->create();
    }

}
