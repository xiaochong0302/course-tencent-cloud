<?php

namespace App\Library\Paginator\Adapter;

use Phalcon\Http\Request as HttpRequest;
use Phalcon\Paginator\Adapter\QueryBuilder as BaseQueryBuilder;

class QueryBuilder extends BaseQueryBuilder
{

    private $url;
    private $params = [];

    public function paginate()
    {
        $pager = parent::paginate();

        $this->initParams();

        $pager->first = $this->buildPageUrl($pager->first);
        $pager->previous = $this->buildPageUrl($pager->previous);
        $pager->next = $this->buildPageUrl($pager->next);
        $pager->last = $this->buildPageUrl($pager->last);

        return $pager;
    }

    private function initParams()
    {
        $request = new HttpRequest();
        
        $params = $request->get();

        if ($params) {
            foreach ($params as $key => $value) {
                if (strlen($value) == 0) {
                    unset($params[$key]);
                }
            }
        }

        $this->params = $params;

        if (!empty($this->params['_url'])) {
            $this->url = $this->params['_url'];
            unset($this->params['_url']);
        } else {
            $this->url = $request->get('_url');
        }
    }

    private function buildPageUrl($page)
    {
        $this->params['page'] = $page;

        $queryUrl = $this->url . '?' . http_build_query($this->params);

        return $queryUrl;
    }

}
