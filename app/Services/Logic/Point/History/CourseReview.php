<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\PointHistory as PointHistoryModel;
use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class CourseReview extends PointHistory
{

    public function handle(ReviewModel $review)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['course_review']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['course_review']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $review->id;

        $eventType = PointHistoryModel::EVENT_COURSE_REVIEW;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($review->owner_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($review->course_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
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
