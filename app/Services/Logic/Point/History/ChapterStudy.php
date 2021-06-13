<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point\History;

use App\Models\ChapterUser as ChapterUserModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory;

class ChapterStudy extends PointHistory
{

    public function handle(ChapterUserModel $chapterUser)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['chapter_study']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['chapter_study']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $chapterUser->id;

        $eventType = PointHistoryModel::EVENT_CHAPTER_STUDY;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($chapterUser->user_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapterUser->course_id);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterUser->chapter_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
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
