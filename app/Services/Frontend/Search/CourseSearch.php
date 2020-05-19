<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class CourseSearch extends FrontendService
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
