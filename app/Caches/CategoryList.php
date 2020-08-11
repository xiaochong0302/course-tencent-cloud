<?php

namespace App\Caches;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;

class CategoryList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($type = null)
    {
        return "category_list:{$type}";
    }

    /**
     * @param null $type
     * @return array
     */
    public function getContent($type = null)
    {
        /**
         * @var Resultset $categories
         */
        $categories = CategoryModel::query()
            ->columns(['id', 'parent_id', 'name', 'priority', 'level', 'path'])
            ->where('type = :type:', ['type' => $type])
            ->andWhere('published = 1')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
