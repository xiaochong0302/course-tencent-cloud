<?php

namespace App\Services\Frontend\Vip;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\Service as FrontendService;

class CourseList extends FrontendService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = 1;
        $params['deleted'] = 0;

        $sort = 'vip';
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $courses = $pager->items->toArray();

        $baseUrl = kg_ci_base_url();

        $items = [];

        foreach ($courses as $course) {

            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'rating' => (float)$course['rating'],
                'model' => $course['model'],
                'level' => $course['level'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
