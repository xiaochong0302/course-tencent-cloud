<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\ChapterSearcher as ChapterSearcherService;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Chapter extends Handler
{

    public function search(): PagerRepoInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new ChapterSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $this->handleKeywords($params['query']),
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->paginate();

        return $this->handleChapters($pager);
    }

    public function getHotQuery(int $limit = 10, string $type = 'total'): array
    {
        $searcher = new ChapterSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery(string $query, int $limit = 10): array
    {
        $searcher = new ChapterSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleChapters(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($pager->getItems() as $item) {

            $course = json_decode($item['course'], true);

            if ($course['cover'] && !str_starts_with($course['cover'], 'http')) {
                $course['cover'] = $baseUrl . $course['cover'];
            }

            $items[] = [
                'id' => (int)$item['id'],
                'title' => (string)$item['title'],
                'summary' => (string)$item['summary'],
                'model' => (int)$item['model'],
                'free' => (int)$item['free'],
                'user_count' => (int)$item['user_count'],
                'like_count' => (int)$item['like_count'],
                'comment_count' => (int)$item['comment_count'],
                'create_time' => (int)$item['create_time'],
                'course' => $course,
            ];
        }

        $pager->setItems($items);

        return $pager;
    }

}
