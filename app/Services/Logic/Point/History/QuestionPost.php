<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\PointHistory as PointHistoryModel;
use App\Models\Question as QuestionModel;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class QuestionPost extends PointHistory
{

    public function handle(QuestionModel $question)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['question_post']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['question_post']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['question_post']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $question->id;

        $eventType = PointHistoryModel::EVENT_QUESTION_POST;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($question->owner_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($question->owner_id);

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
