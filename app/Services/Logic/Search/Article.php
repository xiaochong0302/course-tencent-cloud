<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\ArticleSearcher as ArticleSearcherService;
use Phalcon\Text;

class Article extends Handler
{

    public function search()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new ArticleSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $this->handleKeywords($params['query']),
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->paginate();

        return $this->handleArticles($pager);
    }

    public function getHotQuery($limit = 10, $type = 'total')
    {
        $searcher = new ArticleSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery($query, $limit = 10)
    {
        $searcher = new ArticleSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleArticles($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($pager->items as $item) {

            $category = json_decode($item['category'], true);
            $owner = json_decode($item['owner'], true);
            $tags = json_decode($item['tags'], true);

            if (!empty($item['cover']) && !Text::startsWith($item['cover'], 'http')) {
                $item['cover'] = $baseUrl . $item['cover'];
            }

            $items[] = [
                'id' => (int)$item['id'],
                'title' => (string)$item['title'],
                'cover' => (string)$item['cover'],
                'summary' => (string)$item['summary'],
                'create_time' => (int)$item['create_time'],
                'view_count' => (int)$item['view_count'],
                'like_count' => (int)$item['like_count'],
                'favorite_count' => (int)$item['favorite_count'],
                'comment_count' => (int)$item['comment_count'],
                'category' => $category,
                'owner' => $owner,
                'tags' => $tags,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
