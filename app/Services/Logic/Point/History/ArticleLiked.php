<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\ArticleLike as ArticleLikeModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class ArticleLiked extends PointHistory
{

    public function handle(ArticleLikeModel $articleLike)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['article_liked']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['article_liked']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $dailyPointLimit = $eventRule['article_liked']['limit'] ?? 0;

        if ($dailyPointLimit <= 0) return;

        $eventId = $articleLike->id;

        $eventType = PointHistoryModel::EVENT_ARTICLE_LIKED;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $articleRepo = new ArticleRepo();

        $article = $articleRepo->findById($articleLike->article_id);

        /**
         * @todo 使用缓存优化
         */
        $dailyPoints = $historyRepo->sumUserDailyEventPoints($article->owner_id, $eventType, date('Ymd'));

        if ($dailyPoints >= $dailyPointLimit) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($article->owner_id);

        $eventInfo = [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
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
