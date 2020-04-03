<?php

namespace App\Library\Paginator;

use Phalcon\Mvc\User\Component;

class Query extends Component
{

    public function getPage()
    {
        $page = $this->request->get('page', 'int', 1);

        return $page > 1000 ? 1000 : $page;
    }

    public function getLimit()
    {
        $limit = $this->request->get('limit', 'int', 15);

        return $limit > 100 ? 100 : $limit;
    }

    public function getSort()
    {
        return $this->request->get('sort', 'trim', '');
    }

    public function getUrl()
    {
        return $this->request->get('_url', 'trim', '');
    }

    public function getParams()
    {
        return $this->request->get();
    }

}
