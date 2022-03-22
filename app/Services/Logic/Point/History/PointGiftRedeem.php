<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class PointGiftRedeem extends PointHistory
{

    public function handle(PointGiftRedeemModel $redeem)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventId = $redeem->id;
        $eventType = PointHistoryModel::EVENT_POINT_GIFT_REDEEM;
        $eventPoint = 0 - $redeem->gift_point;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $eventInfo = [
            'point_gift_redeem' => [
                'id' => $redeem->id,
                'gift_id' => $redeem->gift_id,
                'gift_name' => $redeem->gift_name,
                'gift_type' => $redeem->gift_type,
                'gift_point' => $redeem->gift_point,
            ]
        ];

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
