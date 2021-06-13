<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use Phalcon\Di\Injectable;

abstract class Searcher extends Injectable
{

    /**
     * @var \XS
     */
    protected $xs;

    /**
     * 获取XS
     *
     * @return \XS
     */
    abstract public function getXS();

    /**
     * 获取高亮字段
     *
     * @return array
     */
    abstract public function getHighlightFields();

    /**
     * 搜索课程
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \XSException
     */
    public function search($query, $limit = 15, $offset = 0)
    {
        $search = $this->xs->getSearch();

        $docs = $search->setQuery($query)->setLimit($limit, $offset)->search();

        $total = $search->getLastCount();

        $fields = array_keys($this->xs->getAllFields());

        $items = [];

        foreach ($docs as $doc) {
            $item = [];
            foreach ($fields as $field) {
                if (in_array($field, $this->getHighlightFields())) {
                    $item[$field] = $search->highlight($doc->{$field});
                } else {
                    $item[$field] = $doc->{$field};
                }
            }
            $items[] = $item;
        }

        return [
            'total' => $total,
            'items' => $items,
        ];
    }

    /**
     * 获取相关搜索
     *
     * @param string $query
     * @param int $limit
     * @return array
     * @throws \XSException
     */
    public function getRelatedQuery($query, $limit = 10)
    {
        $search = $this->xs->getSearch();

        $search->setQuery($query);

        return $search->getRelatedQuery($query, $limit);
    }

    /**
     * @param int $limit
     * @param string $type [total => 总量, lastnum => 上周, currnum => 本周]
     * @return array
     * @throws \XSException
     */
    public function getHotQuery($limit = 10, $type = 'total')
    {
        $search = $this->xs->getSearch();

        $hotQuery = $search->getHotQuery($limit, $type);

        return array_keys($hotQuery);
    }

}
