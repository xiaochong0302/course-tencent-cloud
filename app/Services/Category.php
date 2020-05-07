<?php

namespace App\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryList as CategoryListCache;

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
     * @param int $id
     * @return array
     */
    public function getChildCategories($id = 0)
    {
        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get();

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

        $category = $categoryCache->get($id);

        if (!$category) {
            return [];
        }

        if ($category->level == 2) {
            return [$id];
        }

        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get();

        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $id) {
                $result[] = $category['id'];
            }
        }

        return $result;
    }

}
