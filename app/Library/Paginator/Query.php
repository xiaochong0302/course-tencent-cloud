<?php

namespace App\Library\Paginator;

use Phalcon\Di;
use Phalcon\Http\Request;

class Query
{

    /**
     * @var Request
     */
    protected $request;

    public function __construct()
    {
        $this->request = Di::getDefault()->get('request');
    }

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

    public function getBaseUrl()
    {
        return $this->request->get('_url', 'trim', '');
    }

    public function getParams()
    {
        $params = $this->request->get();

        if ($params) {
            foreach ($params as $key => $value) {
                if (strlen($value) == 0) {
                    unset($params[$key]);
                }
            }
        }

        if (isset($params['_url'])) {
            unset($params['_url']);
        }

        return $params;
    }

}
