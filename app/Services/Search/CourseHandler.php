<?php

namespace App\Services\Search;

use Phalcon\Mvc\User\Component;

class CourseHandler extends Component
{

    /**
     * @var \XS
     */
    protected $xs;

    public function __construct()
    {
        $fileName = config_path() . '/xs.course.ini';

        $this->xs = new \XS($fileName);
    }

    /**
     * 获取XS
     *
     * @return \XS
     */
    public function getXS()
    {
        return $this->xs;
    }

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
                if (in_array($field, ['title', 'summary'])) {
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

}
