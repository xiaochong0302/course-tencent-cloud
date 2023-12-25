<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Paginator;

use Phalcon\Di;
use Phalcon\Filter;
use Phalcon\Http\Request;

class Query
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Filter
     */
    protected $filter;

    public function __construct()
    {
        $this->request = Di::getDefault()->get('request');

        $this->filter = Di::getDefault()->get('filter');
    }

    public function getPage()
    {
        $page = $this->request->getQuery('page', ['trim', 'int'], 1);

        return min($page, 100);
    }

    public function getLimit()
    {
        $limit = $this->request->getQuery('limit', ['trim', 'int'], 12);

        return min($limit, 100);
    }

    public function getSort()
    {
        return $this->request->getQuery('sort', ['trim', 'string']);
    }

    public function getBaseUrl()
    {
        return $this->request->getQuery('_url', ['trim', 'string']);
    }

    public function getParams(array $whitelist = [])
    {
        $params = $this->request->getQuery();

        if ($params) {
            foreach ($params as $key => &$value) {
                $value = $this->filter->sanitize($value, ['trim', 'string']);
                if ($whitelist && !in_array($value, $whitelist)) {
                    unset($params[$key]);
                } elseif (!is_array($value) && strlen($value) == 0) {
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
