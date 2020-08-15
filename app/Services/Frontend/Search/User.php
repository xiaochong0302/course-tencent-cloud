<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\UserSearcher as UserSearcherService;

class User extends FrontendService
{

    public function search()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new UserSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $params['query'],
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->getPaginate();

        return $this->handleUsers($pager);
    }

    public function hotQuery($limit = 10, $type = 'total')
    {
        $searcher = new UserSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function relatedQuery($query, $limit = 10)
    {
        $searcher = new UserSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleUsers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        foreach ($pager->items as $item) {
            $items[] = [
                'id' => (int)$item['id'],
                'name' => $item['name'],
                'avatar' => $item['avatar'],
                'title' => $item['title'],
                'about' => $item['about'],
                'vip' => (int)$item['vip'],
                'gender' => (int)$item['gender'],
                'location' => $item['location'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
