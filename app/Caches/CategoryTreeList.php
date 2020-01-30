<?php

namespace App\Caches;

use App\Builders\CategoryList as CategoryTreeListBuilder;
use App\Models\Category as CategoryModel;

class CategoryTreeList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'category_tree';
    }

    public function getContent($id = null)
    {
        $categories = CategoryModel::query()
            ->where('published = 1 AND deleted = 0')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $this->handleContent($categories);
    }

    /**
     * @param \App\Models\Category[] $categories
     * @return array
     */
    protected function handleContent($categories)
    {
        $items = $categories->toArray();

        $builder = new CategoryTreeListBuilder();

        $content = $builder->handleTreeList($items);

        return $content;
    }

}
