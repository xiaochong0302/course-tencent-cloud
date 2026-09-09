<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Paginator;

use Phalcon\Di\Di;
use Phalcon\Filter\Filter;
use Phalcon\Http\Request;

class Query
{

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Filter
     */
    protected Filter $filter;

    public function __construct()
    {
        $this->request = Di::getDefault()->get('request');
        $this->filter = Di::getDefault()->get('filter');
    }

    public function getPage(): int
    {
        $page = $this->request->getQuery('page', ['trim', 'int'], 1);

        return min($page, 100);
    }

    public function getLimit(): int
    {
        $limit = $this->request->getQuery('limit', ['trim', 'int'], 12);

        return min($limit, 100);
    }

    public function getSort(): string
    {
        return $this->request->getQuery('sort', ['trim', 'string'], 'latest');
    }

    public function getBaseUrl(): string
    {
        return $this->request->getQuery('_url', ['trim', 'string'], '/');
    }

    public function getParams(array $whitelist = []): array
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
