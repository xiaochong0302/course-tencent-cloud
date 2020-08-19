<?php

namespace App\Caches;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use Phalcon\Mvc\Model\Resultset;

/**
 * 直播课程
 */
class IndexLiveList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_live_list';
    }

    public function getContent($id = null)
    {
        /**
         * 限制输出多少天数（一维限额）
         */
        $dayLimit = 3;

        /**
         * 限制每天维度下的输出数（二维限额）
         */
        $perDayLimit = 10;

        $beginTime = strtotime('today');
        $endTime = strtotime("+30 days");

        /**
         * @var Resultset|ChapterLiveModel[] $lives
         */
        $lives = ChapterLiveModel::query()
            ->betweenWhere('start_time', $beginTime, $endTime)
            ->orderBy('start_time ASC')
            ->execute();

        if ($lives->count() == 0) {
            return [];
        }

        $result = [];

        $chapterIds = kg_array_column($lives->toArray(), 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($chapterIds);

        $chapterMappings = [];

        foreach ($chapters as $chapter) {
            $chapterMappings[$chapter->id] = $chapter;
        }

        $courseIds = kg_array_column($lives->toArray(), 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        $courseMappings = [];

        foreach ($courses as $course) {
            $courseMappings[$course->id] = $course;
        }

        foreach ($lives as $live) {

            if (count($result) >= $dayLimit) {
                break;
            }

            $day = date('y-m-d', $live->start_time);

            if (isset($result[$day]) && count($result[$day]) >= $perDayLimit) {
                continue;
            }

            $chapter = $chapterMappings[$live->chapter_id];
            $course = $courseMappings[$chapter->course_id];

            $chapterInfo = [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'start_time' => $live->start_time,
                'end_time' => $live->end_time,
            ];

            $courseInfo = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
            ];

            $result[$day][] = [
                'course' => $courseInfo,
                'chapter' => $chapterInfo,
            ];
        }

        return $result;
    }

}
