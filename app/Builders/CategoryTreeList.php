<?php

namespace App\Builders;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CategoryTreeList extends Builder
{

    public function handle($type)
    {
        $topCategories = $this->findChildCategories($type, 0);

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
        $subCategories = $this->findChildCategories($category->type, $category->id);

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
     * @param string $type
     * @param int $parentId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findChildCategories($type = 'course', $parentId = 0)
    {
        $query = CategoryModel::query();

        $query->where('published = 1');

        if ($type) {
            $query->andWhere('type = :type:', ['type' => $type]);
        }

        if ($parentId) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $parentId]);
        }

        $query->orderBy('priority ASC');

        return $query->execute();
    }

}
