<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Paginator\Adapter;

use App\Library\Paginator\Query as PaginatorQuery;
use App\Library\Validators\Common as CommonValidator;
use Phalcon\Paginator\Adapter as PaginatorAdapter;
use Phalcon\Paginator\Exception as PaginatorException;
use stdClass;

/**
 *
 * Pagination using xunsearch as source of data
 *
 * <code>
 * use App\Library\Paginator\Adapter\XunSearch;
 *
 * $paginator = new XunSearch(
 *     [
 *         "xs"  => $xs,
 *         "query"  => $query,
 *         "highlight"  => $highlight,
 *         "page"  => $page,
 *         "limit" => $limit,
 *     ]
 * );
 *</code>
 */
class XunSearch extends PaginatorAdapter
{

    protected $config;

    protected $baseUrl;

    protected $params = [];

    public function __construct(array $config)
    {
        if (!isset($config['xs']) || !($config['xs'] instanceof \XS)) {
            throw new PaginatorException('Invalid xs parameter');
        }

        if (empty($config['query'])) {
            throw new PaginatorException('Invalid query parameter');
        }

        if (isset($config['page']) && !CommonValidator::positiveNumber($config['page'])) {
            throw new PaginatorException('Invalid page parameter');
        }

        if (isset($config['limit']) && !CommonValidator::positiveNumber($config['limit'])) {
            throw new PaginatorException('Invalid limit parameter');
        }

        if (isset($config['highlight']) && !is_array($config['highlight'])) {
            throw new PaginatorException('Invalid highlight parameter');
        }

        $this->config = $config;
        $this->_page = $config['page'] ?? 1;
        $this->_limitRows = $config['limit'] ?? 12;

        $query = new PaginatorQuery();

        $this->baseUrl = $query->getBaseUrl();
        $this->params = $query->getParams();
    }

    public function paginate(): stdClass
    {
        /**
         * @var \XS $xs
         */
        $xs = $this->config['xs'];

        $page = $this->_page;
        $limit = $this->_limitRows;
        $offset = ($page - 1) * $limit;

        $search = $xs->getSearch();

        $docs = $search->setQuery($this->config['query'])
            ->setLimit($limit, $offset)
            ->search();

        $totalCount = $search->getLastCount();

        $fields = array_keys($xs->getAllFields());

        $items = [];

        foreach ($docs as $doc) {
            $item = [];
            foreach ($fields as $field) {
                if (in_array($field, $this->config['highlight'])) {
                    $item[$field] = $search->highlight($doc->{$field});
                } else {
                    $item[$field] = $doc->{$field};
                }
            }
            $items[] = $item;
        }

        $totalPages = ceil($totalCount / $limit);

        $pager = new stdClass();

        $pager->first = 1;
        $pager->previous = $page > 1 ? $page - 1 : 1;
        $pager->next = $page < $totalPages ? $page + 1 : $page;
        $pager->last = $totalPages;
        $pager->total_items = $totalCount;
        $pager->total_pages = $totalPages;
        $pager->items = $items;

        $pager->first = $this->buildPageUrl($pager->first);
        $pager->previous = $this->buildPageUrl($pager->previous);
        $pager->next = $this->buildPageUrl($pager->next);
        $pager->last = $this->buildPageUrl($pager->last);

        return $pager;
    }

    public function getPaginate(): stdClass
    {
        return $this->paginate();
    }

    protected function buildPageUrl($page)
    {
        $this->params['page'] = $page;

        return $this->baseUrl . '?' . http_build_query($this->params);
    }

}
