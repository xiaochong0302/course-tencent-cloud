<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\GroupSearcher as GroupSearcherService;

class Group extends FrontendService
{

    public function search()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $searcher = new GroupSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $searcher->getXS(),
            'highlight' => $searcher->getHighlightFields(),
            'query' => $params['query'],
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->getPaginate();

        return $this->handleGroups($pager);
    }

    public function hotQuery($limit = 10, $type = 'total')
    {
        $searcher = new GroupSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function relatedQuery($query, $limit = 10)
    {
        $searcher = new GroupSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

    protected function handleGroups($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($pager->items as $item) {

            $item['avatar'] = $baseUrl . $item['avatar'];

            $items[] = [
                'id' => (int)$item['id'],
                'type' => $item['type'],
                'name' => $item['name'],
                'avatar' => $item['avatar'],
                'about' => $item['about'],
                'user_count' => (int)$item['user_count'],
                'owner' => json_decode($item['owner'], true),
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
