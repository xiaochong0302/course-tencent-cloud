<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\Comment as CommentModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class CommentPost extends PointHistory
{

    public function handle(CommentModel $comment)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['comment_post']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['comment_post']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['comment_post']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $comment->id;

        $eventType = PointHistoryModel::EVENT_COMMENT_POST;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($comment->owner_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($comment->owner_id);

        $commentContent = kg_substr($comment->content, 0, 32);

        $eventInfo = [
            'comment' => [
                'id' => $comment->id,
                'content' => $commentContent,
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
