<?php

namespace App\Services\Logic\Teacher\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Teacher as TeacherRepo;
use App\Services\Logic\Service;

class LiveList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $teacherRepo = new TeacherRepo();

        $courses = $teacherRepo->findLiveCourses($user->id);

        if ($courses->count() == 0) {
            return [];
        }

        $courseMapping = [];

        foreach ($courses as $course) {
            $courseMapping[$course->id] = [
                'id' => $course->id,
                'title' => $course->title,
            ];
        }

        $courseIds = kg_array_column($courses->toArray(), 'id');

        $pagerQuery = new PagerQuery();

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pager = $teacherRepo->paginateLiveChapters($courseIds, $page, $limit);

        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        foreach ($pager->items as $item) {
            $items[] = [
                'course' => $courseMapping[$item->course_id],
                'chapter' => [
                    'id' => $item->id,
                    'title' => $item->title,
                ],
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
