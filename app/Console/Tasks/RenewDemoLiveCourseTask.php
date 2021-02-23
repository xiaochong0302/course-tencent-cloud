<?php

namespace App\Console\Tasks;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;

class RenewDemoLiveCourseTask extends Task
{

    /**
     * 25号顺延直播课程日期（日期顺延一个月）
     */
    public function mainAction()
    {
        $day = date('d');

        if ($day != 25) return;

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById(1393);

        $chapters = $courseRepo->findLessons($course->id);

        $chapterRepo = new ChapterRepo();

        foreach ($chapters as $chapter) {

            $live = $chapterRepo->findChapterLive($chapter->id);

            if ($live->start_time > time()) {
                continue;
            }

            $startTime = strtotime('+1 month', $live->start_time);
            $endTime = strtotime('+1 month', $live->end_time);

            $live->start_time = $startTime;
            $live->end_time = $endTime;

            $live->update();

            $attrs = $chapter->attrs;

            $attrs['start_time'] = $startTime;
            $attrs['end_time'] = $endTime;

            $chapter->attrs = $attrs;

            $chapter->update();
        }

        $statService = new CourseStatService();

        $statService->updateLiveAttrs($course->id);

        $cache = new CourseChapterListCache();

        $cache->rebuild($course->id);
    }

}
