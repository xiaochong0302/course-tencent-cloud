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
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Course extends Handler
{

    public function search(): PagerRepoInterface
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

    public function getHotQuery(int $limit = 10, string $type = 'total'): array
    {
        $searcher = new CourseSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery(string $query, int $limit = 10): array
    {
        $searcher = new CourseSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleCourses(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($pager->getItems() as $item) {

            $category = json_decode($item['category'], true);
            $teacher = json_decode($item['teacher'], true);
            $tags = json_decode($item['tags'], true);

            if ($item['cover'] && !str_starts_with($item['cover'], 'http')) {
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
                'study_expiry' => (int)$item['study_expiry'],
                'refund_expiry' => (int)$item['refund_expiry'],
                'user_count' => (int)$item['user_count'],
                'lesson_count' => (int)$item['lesson_count'],
                'review_count' => (int)$item['review_count'],
                'favorite_count' => (int)$item['favorite_count'],
                'create_time' => (int)$item['create_time'],
                'category' => $category,
                'teacher' => $teacher,
                'tags' => $tags,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
