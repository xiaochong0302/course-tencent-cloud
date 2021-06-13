<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\ImMessage as ImMessageModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class ImDiscuss extends PointHistory
{

    public function handle(ImMessageModel $message)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['im_discuss']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['im_discuss']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $message->sender_id;
        $eventType = PointHistoryModel::EVENT_IM_DISCUSS;
        $eventInfo = new \stdClass();

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($message->sender_id);

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
