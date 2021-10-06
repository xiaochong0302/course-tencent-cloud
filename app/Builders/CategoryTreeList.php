<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\Category as CategoryModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CategoryTreeList extends Builder
{

    public function handle($type)
    {
        $topCategories = $this->findTopCategories($type);

        if ($topCategories->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($topCategories as $category) {
            $list[] = [
                'id' => $category->id,
                'name' => $category->name,
                'alias' => $category->alias,
                'icon' => $category->icon,
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
                'alias' => $category->alias,
                'icon' => $category->icon,
            ];
        }

        return $list;
    }

    /**
     * @param int $type
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findTopCategories($type)
    {
        $query = CategoryModel::query();

        $query->where('parent_id = 0');
        $query->andWhere('published = 1');
        $query->andWhere('deleted = 0');
        $query->andWhere('type = :type:', ['type' => $type]);
        $query->orderBy('priority ASC');

        return $query->execute();
    }

    /**
     * @param int $parentId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findChildCategories($parentId)
    {
        $query = CategoryModel::query();

        $query->where('published = 1');
        $query->where('deleted = 0');
        $query->andWhere('parent_id = :parent_id:', ['parent_id' => $parentId]);
        $query->orderBy('priority ASC');

        return $query->execute();
    }

}
