<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class Course extends Handler
{

    public function search()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new CourseSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $params['query'],
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->getPaginate();

        return $this->handleCourses($pager);
    }

    public function getHotQuery($limit = 10, $type = 'total')
    {
        $searcher = new CourseSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery($query, $limit = 10)
    {
        $searcher = new CourseSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($pager->items as $item) {

            $item['cover'] = $baseUrl . $item['cover'];

            $items[] = [
                'id' => (int)$item['id'],
                'title' => $item['title'],
                'cover' => $item['cover'],
                'summary' => $item['summary'],
                'model' => $item['model'],
                'level' => $item['level'],
                'market_price' => (float)$item['market_price'],
                'vip_price' => (float)$item['vip_price'],
                'user_count' => (int)$item['user_count'],
                'lesson_count' => (int)$item['lesson_count'],
                'review_count' => (int)$item['review_count'],
                'favorite_count' => (int)$item['favorite_count'],
                'teacher' => json_decode($item['teacher'], true),
                'category' => json_decode($item['category'], true),
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
