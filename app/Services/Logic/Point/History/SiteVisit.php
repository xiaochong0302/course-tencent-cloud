<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\PointHistory as PointHistoryModel;
use App\Models\User as UserModel;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Services\Logic\Point\PointHistory;

class SiteVisit extends PointHistory
{

    public function handle(UserModel $user)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['site_visit']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['site_visit']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $user->id;
        $eventType = PointHistoryModel::EVENT_SITE_VISIT;
        $eventInfo = [];

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

}
