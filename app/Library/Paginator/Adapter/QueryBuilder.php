<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Paginator\Adapter;

use App\Library\Paginator\Query as PaginatorQuery;
use Phalcon\Paginator\Adapter\QueryBuilder as PhQueryBuilder;
use Phalcon\Paginator\RepositoryInterface;

class QueryBuilder extends PhQueryBuilder
{

    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var array
     */
    protected array $params = [];

    /**
     * @var array
     */
    protected array $properties = [];

    public function paginate(): RepositoryInterface
    {
        $pager = parent::paginate();

        $query = new PaginatorQuery();

        $this->baseUrl = $query->getBaseUrl();
        $this->params = $query->getParams();

        $properties = [
            'total_items' => $pager->getTotalItems(),
            'items' => $pager->getItems(),
            'current' => $pager->getCurrent(),
            'limit' => $pager->getLimit(),
            'first' => $this->buildPageUrl($pager->getFirst()),
            'previous' => $this->buildPageUrl($pager->getPrevious()),
            'next' => $this->buildPageUrl($pager->getNext()),
            'last' => $this->buildPageUrl($pager->getLast()),
        ];

        $pager->setProperties($properties);

        return $pager;
    }

    protected function buildPageUrl(int $page): string
    {
        $this->params['page'] = $page;

        return $this->baseUrl . '?' . http_build_query($this->params);
    }

}
