<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\Internal;

use App\Models\Answer as AnswerModel;
use App\Models\Comment as CommentModel;
use App\Models\Notification as NotificationModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\Service as LogicService;

class AnswerCommented extends LogicService
{

    public function handle(AnswerModel $answer, CommentModel $comment)
    {
        $answerSummary = kg_substr($answer->summary, 0, 32);
        $commentContent = kg_substr($comment->content, 0, 36);

        $question = $this->findQuestion($answer->question_id);

        $notification = new NotificationModel();

        $notification->sender_id = $comment->owner_id;
        $notification->receiver_id = $answer->owner_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationModel::TYPE_ANSWER_COMMENTED;
        $notification->event_info = [
            'question' => ['id' => $question->id, 'title' => $question->title],
            'answer' => ['id' => $answer->id, 'summary' => $answerSummary],
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->create();
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        return $questionRepo->findById($id);
    }

}
