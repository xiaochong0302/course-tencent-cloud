<?php

namespace App\Caches;

use App\Models\Category as CategoryModel;

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
        $categories = CategoryModel::query()
            ->columns(['id', 'parent_id', 'name', 'priority', 'level', 'path'])
            ->where('published = 1 AND deleted = 0')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
