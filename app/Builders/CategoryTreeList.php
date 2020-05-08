<?php

namespace App\Builders;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CategoryTreeList extends Builder
{

    public function handle()
    {
        $topCategories = $this->findChildCategories(0);

        if ($topCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($topCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'children' => $this->handleChildren($category),
            ];
        }

        return $list;
    }

    protected function handleChildren(CategoryModel $category)
    {
        $subCategories = $this->findChildCategories($category->id);

        if ($subCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($subCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        return $list;
    }

    /**
     * @param int $categoryId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findChildCategories($categoryId = 0)
    {
        return CategoryModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $categoryId])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
