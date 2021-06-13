<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;

class Category extends Service
{

    /**
     * 获取节点路径
     *
     * @param int $id
     * @return array
     */
    public function getCategoryPaths($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 1) {
            return [
                [
                    'id' => $category->id,
                    'name' => $category->name,
                ]
            ];
        }

        $parent = $categoryCache->get($category->parent_id);

        return [
            [
                'id' => $parent->id,
                'name' => $parent->name,
            ],
            [
                'id' => $category->id,
                'name' => $category->name,
            ]
        ];
    }

    /**
     * 获取子节点
     *
     * @param string $type
     * @param int $id
     * @return array
     */
    public function getChildCategories($type, $id)
    {
        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get($type);

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category;
            }
        }

        return $result;
    }

    /**
     * 获取子节点ID
     *
     * @param int $id
     * @return array
     */
    public function getChildCategoryIds($id)
    {
        $categoryCache = new CategoryCache();

        /**
         * @var CategoryModel $category
         */
        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 2) {
            return [$id];
        }

        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get($category->type);

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category['id'];
            }
        }

        return $result;
    }

}
