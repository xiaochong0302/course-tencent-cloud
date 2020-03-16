<?php

namespace App\Library\Paginator;

use Phalcon\Mvc\User\Component;

class Query extends Component
{

    public function getPage()
    {
        $page = $this->request->get('page', 'int', 1);

        $result = $page > 1000 ? 1000 : $page;

        return $result;
    }

    public function getLimit()
    {
        $limit = $this->request->get('limit', 'int', 15);

        $result = $limit > 100 ? 100 : $limit;

        return $result;
    }

    public function getSort()
    {
        $sort = $this->request->get('sort', 'trim', '');

        return $sort;
    }

    public function getUrl()
    {
        $url = $this->request->get('_url', 'trim', '');

        return $url;
    }

    public function getParams()
    {
        $params = $this->request->get();

        return $params;
    }

}
