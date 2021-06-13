<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Search\UserSearcher as UserSearcherService;

class User extends Handler
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

    public function getHotQuery($limit = 10, $type = 'total')
    {
        $searcher = new UserSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

    public function getRelatedQuery($query, $limit = 10)
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

        $baseUrl = kg_cos_url();

        foreach ($pager->items as $item) {

            $item['avatar'] = $baseUrl . $item['avatar'];

            $items[] = [
                'id' => (int)$item['id'],
                'name' => (string)$item['name'],
                'avatar' => (string)$item['avatar'],
                'title' => (string)$item['title'],
                'about' => (string)$item['about'],
                'vip' => (int)$item['vip'],
                'gender' => (int)$item['gender'],
                'area' => (string)$item['area'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
