<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Paginator\Adapter;

use App\Library\Paginator\Query as PaginatorQuery;
use App\Library\Validators\Common as CommonValidator;
use Phalcon\Paginator\Adapter\AbstractAdapter;
use Phalcon\Paginator\Exception as PaginatorException;
use Phalcon\Paginator\RepositoryInterface;
use XS;

/**
 *
 * Pagination using xunsearch as source of data
 *
 * <code>
 * use App\Library\Paginator\Adapter\XunSearch;
 *
 * $paginator = new XunSearch(
 *     [
 *         "xs" => $xs,
 *         "query" => $query,
 *         "highlight" => $highlight,
 *         "page" => $page,
 *         "limit" => $limit,
 *     ]
 * );
 *</code>
 */
class XunSearch extends AbstractAdapter
{

    /**
     * @var XS
     */
    protected XS $xs;

    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var array
     */
    protected array $params = [];

    public function __construct(array $config)
    {
        if (!isset($config['xs']) || !($config['xs'] instanceof XS)) {
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

        $config['page'] = $config['page'] ?? 1;
        $config['limit'] = $config['limit'] ?? 12;

        parent::__construct($config);

        $query = new PaginatorQuery();

        $this->xs = $this->config['xs'];
        $this->baseUrl = $query->getBaseUrl();
        $this->params = $query->getParams();
    }

    public function paginate(): RepositoryInterface
    {
        /**
         * @var XS $xs
         */
        $xs = $this->config['xs'];

        $page = $this->page;
        $limit = $this->limitRows;
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

        $properties = [
            'first' => 1,
            'previous' => $page > 1 ? $page - 1 : 1,
            'next' => $page < $totalPages ? $page + 1 : $page,
            'last' => $totalPages,
            'current' => $page,
            'limit' => $limit,
            'total_pages' => $totalPages,
            'total_items' => $totalCount,
            'items' => $items,
        ];

        $properties['first'] = $this->buildPageUrl($properties['first']);
        $properties['previous'] = $this->buildPageUrl($properties['previous']);
        $properties['next'] = $this->buildPageUrl($properties['next']);
        $properties['last'] = $this->buildPageUrl($properties['last']);

        return $this->getRepository($properties);
    }

    protected function buildPageUrl(int $page): string
    {
        $this->params['page'] = $page;

        return $this->baseUrl . '?' . http_build_query($this->params);
    }

}
