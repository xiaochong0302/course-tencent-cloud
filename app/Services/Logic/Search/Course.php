<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\CourseSearcher as CourseSearcherService;
use Phalcon\Text;

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
            'query' => $this->handleKeywords($params['query']),
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->paginate();

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

            /**
             * 后补的字段，给默认值防止出错
             */
            $item['tags'] = $item['tags'] ?: '[]';

            $category = json_decode($item['category'], true);
            $teacher = json_decode($item['teacher'], true);
            $tags = json_decode($item['tags'], true);

            if (!empty($item['cover']) && !Text::startsWith($item['cover'], 'http')) {
                $item['cover'] = $baseUrl . $item['cover'];
            }

            $items[] = [
                'id' => (int)$item['id'],
                'title' => (string)$item['title'],
                'cover' => (string)$item['cover'],
                'summary' => (string)$item['summary'],
                'model' => (int)$item['model'],
                'level' => (int)$item['level'],
                'rating' => round($item['rating'], 1),
                'market_price' => (float)$item['market_price'],
                'vip_price' => (float)$item['vip_price'],
                'user_count' => (int)$item['user_count'],
                'lesson_count' => (int)$item['lesson_count'],
                'review_count' => (int)$item['review_count'],
                'favorite_count' => (int)$item['favorite_count'],
                'category' => $category,
                'teacher' => $teacher,
                'tags' => $tags,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
