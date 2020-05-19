<?php

namespace App\Library\Paginator\Adapter;

use App\Library\Paginator\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as BaseQueryBuilder;

class QueryBuilder extends BaseQueryBuilder
{

    protected $baseUrl;

    protected $params = [];

    public function paginate()
    {
        $pager = parent::paginate();

        $query = new Query();

        $this->baseUrl = $query->getBaseUrl();
        $this->params = $query->getParams();

        $pager->first = $this->buildPageUrl($pager->first);
        $pager->previous = $this->buildPageUrl($pager->previous);
        $pager->next = $this->buildPageUrl($pager->next);
        $pager->last = $this->buildPageUrl($pager->last);

        return $pager;
    }

    protected function buildPageUrl($page)
    {
        $this->params['page'] = $page;

        return $this->baseUrl . '?' . http_build_query($this->params);
    }

}
