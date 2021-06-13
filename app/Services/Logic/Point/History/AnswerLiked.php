<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\AnswerLike as AnswerLikeModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\Answer as AnswerRepo;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class AnswerLiked extends PointHistory
{

    public function handle(AnswerLikeModel $answerLike)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['answer_liked']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['answer_liked']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['answer_liked']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $answerLike->id;

        $eventType = PointHistoryModel::EVENT_ANSWER_LIKED;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $answerRepo = new AnswerRepo();

        $answer = $answerRepo->findById($answerLike->answer_id);

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($answer->owner_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($answer->question_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($answer->owner_id);

        $eventInfo = [
            'question' => [
                'id' => $question->id,
                'title' => $question->title,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

}
