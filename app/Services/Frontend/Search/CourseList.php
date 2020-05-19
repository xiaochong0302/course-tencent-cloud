<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class CourseList extends FrontendService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseSearcher = new CourseSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $courseSearcher->getXS(),
            'highlight' => $courseSearcher->getHighlightFields(),
            'query' => $params['query'],
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->getPaginate();

        return $this->handleCourses($pager);
    }

    public function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        foreach ($pager->items as $course) {
            $items[] = [
                'id' => (int)$course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'summary' => $course['summary'],
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'model' => $course['model'],
                'level' => $course['level'],
                'user_count' => (int)$course['user_count'],
                'lesson_count' => (int)$course['lesson_count'],
                'teacher' => json_decode($course['teacher']),
                'category' => json_decode($course['category']),
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
