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

    public function getKey($id = null)
    {
        return 'category_list';
    }

    /**
     * @param null $id
     * @return array
     */
    public function getContent($id = null)
    {
        /**
         * @var Resultset $categories
         */
        $categories = CategoryModel::query()
            ->columns(['id', 'parent_id', 'name', 'priority', 'level', 'path'])
            ->where('published = 1')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
