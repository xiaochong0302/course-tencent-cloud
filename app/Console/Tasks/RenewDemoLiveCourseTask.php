<?php

namespace App\Console\Tasks;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
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
        if (date('d') != 25) return;

        $courseRepo = new CourseRepo();

        $courses = $this->findLiveCourses();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {

            $lessons = $courseRepo->findLessons($course->id);

            foreach ($lessons as $lesson) {
                $this->handleLesson($lesson);
            }

            $statService = new CourseStatService();

            $statService->updateLiveAttrs($course->id);

            $cache = new CourseChapterListCache();

            $cache->rebuild($course->id);
        }
    }

    protected function handleLesson(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        if ($live->start_time > time()) return;

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

    protected function findLiveCourses($limit = 8)
    {
        return CourseModel::query()
            ->where('model = :model:', ['model' => CourseModel::MODEL_LIVE])
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

}
