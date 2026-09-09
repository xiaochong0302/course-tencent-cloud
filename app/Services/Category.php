<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Category as CategoryModel;

class Category extends Service
{

    /**
     * 获取子节点ID（包含所有后代）
     *
     * @param int $id
     * @return array
     */
    public function getChildCategoryIds(int $id): array
    {
        $categoryCache = new CategoryCache();

        /**
         * @var CategoryModel $category
         */
        $category = $categoryCache->get($id);

        if (!$category) return [];

        $categoryListCache = new CategoryListCache();

        $categories = $categoryListCache->get($category->type);

        $result = [];

        foreach ($categories as $category) {
            if (str_contains($category['path'], ",{$id},")) {
                $result[] = $category['id'];
            }
        }

        return $result;
    }

    /**
     * 获取子节点（只包含直系后代）
     *
     * @param int $type
     * @param int $id
     * @return array
     */
    public function getChildCategories(int $type, int $id): array
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
     * 获取节点路径（A->B->C）
     *
     * @param int $id
     * @return array
     */
    public function getCategoryPaths(int $id): array
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) return [];

        $categoryIds = explode(',', trim($category->path, ','));

        $paths = [];

        foreach ($categoryIds as $categoryId) {
            /**
             * @var CategoryModel $category
             */
            $category = $categoryCache->get($categoryId);

            $paths[] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        return $paths;
    }

    /**
     * 获取分类Select选项
     *
     * @param int $type
     * @param int $maxLevel 最大显示层级，0表示不限制
     * @return array
     */
    public function getCategoryOptions(int $type, int $maxLevel = 0): array
    {
        $cache = new CategoryTreeListCache();

        $categories = $cache->get($type);

        $result = [];

        if (!$categories) return $result;

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
            ];

            if (count($category['children']) > 0 && ($maxLevel == 0 || $maxLevel >= 2)) {
                $result = array_merge($result, $this->buildChildrenOptions($category['children'], 2, $maxLevel));
            }
        }

        return $result;
    }

    /**
     * 递归构建子分类Select选项
     *
     * @param array $children
     * @param int $currentLevel 当前实际层级（从2开始）
     * @param int $maxLevel 最大显示层级，0表示不限制
     * @return array
     */
    protected function buildChildrenOptions(array $children, int $currentLevel, int $maxLevel = 0): array
    {
        $result = [];

        foreach ($children as $child) {
            $prefix = str_repeat('|--- ', $currentLevel - 1);

            $result[] = [
                'id' => $child['id'],
                'name' => $prefix . $child['name'],
            ];

            if (count($child['children']) > 0 && ($maxLevel == 0 || $currentLevel < $maxLevel)) {
                $result = array_merge($result, $this->buildChildrenOptions($child['children'], $currentLevel + 1, $maxLevel));
            }
        }

        return $result;
    }

    /**
     * 获取分类XmSelect选项
     *
     * @param int $type
     * @param array $selectedIds
     * @return array
     */
    public function getCategoryXmOptions(int $type, array $selectedIds = []): array
    {
        $cache = new CategoryTreeListCache();

        $categories = $cache->get($type);

        if (!$categories) return [];

        return $this->buildChildXmOptions($categories, $selectedIds);
    }

    /**
     * 递归构建子分类XmSelect选项
     *
     * @param array $categories
     * @param array $selectedIds
     * @return array
     */
    protected function buildChildXmOptions(array $categories, array $selectedIds = []): array
    {
        $result = [];

        foreach ($categories as $category) {
            $node = [
                'name' => $category['name'],
                'value' => $category['id'],
                'selected' => in_array($category['id'], $selectedIds),
            ];

            if (!empty($category['children'])) {
                $node['children'] = $this->buildChildXmOptions($category['children'], $selectedIds);
            }

            $result[] = $node;
        }

        return $result;
    }

}
