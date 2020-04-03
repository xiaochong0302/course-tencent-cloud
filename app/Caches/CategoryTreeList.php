<?php

namespace App\Caches;

use App\Builders\CategoryTreeList as CategoryTreeListBuilder;
use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;

class CategoryTreeList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'category_tree_list';
    }

    public function getContent($id = null)
    {
        /**
         * @var Resultset $categories
         */
        $categories = CategoryModel::query()
            ->where('published = 1 AND deleted = 0')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $this->handleContent($categories);
    }

    /**
     * @param Resultset $categories
     * @return array
     */
    protected function handleContent($categories)
    {
        $items = $categories->toArray();

        $builder = new CategoryTreeListBuilder();

        return $builder->handleTreeList($items);
    }

}
