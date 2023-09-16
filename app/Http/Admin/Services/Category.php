<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;
use App\Validators\Category as CategoryValidator;

class Category extends Service
{

    public function getCategoryOptions($type)
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
            if (count($category['children']) > 0) {
                foreach ($category['children'] as $child) {
                    $result[] = [
                        'id' => $child['id'],
                        'name' => sprintf('|--- %s', $child['name']),
                    ];
                }
            }
        }

        return $result;
    }

    public function showAddButton($type, CategoryModel $parent)
    {
        $result = true;

        if ($type == CategoryModel::TYPE_COURSE) {
            $result = $parent->level < 2;
        } elseif ($type == CategoryModel::TYPE_HELP) {
            $result = $parent->level < 1;
        } elseif ($type == CategoryModel::TYPE_ARTICLE) {
            $result = $parent->level < 1;
        } elseif ($type == CategoryModel::TYPE_QUESTION) {
            $result = $parent->level < 1;
        }

        return $result;
    }

    public function getCategory($id)
    {
        return $this->findOrFail($id);
    }

    public function getParentCategory($id)
    {
        if ($id > 0) {
            $parent = CategoryModel::findFirst($id);
        } else {
            $parent = new CategoryModel();
            $parent->id = 0;
            $parent->level = 0;
        }

        return $parent;
    }

    public function getTopCategories($type)
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'parent_id' => 0,
            'deleted' => 0,
            'type' => $type,
        ]);
    }

    public function getChildCategories($parentId)
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ]);
    }

    public function createCategory()
    {
        $post = $this->request->getPost();

        $validator = new CategoryValidator();

        $data = [
            'parent_id' => 0,
            'published' => 1,
        ];

        $parent = null;

        if ($post['parent_id'] > 0) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
        }

        $data['type'] = $validator->checkType($post['type']);
        $data['name'] = $validator->checkName($post['name']);
        $data['priority'] = $validator->checkPriority($post['priority']);

        $category = new CategoryModel();

        $category->create($data);

        if ($parent) {
            $category->path = $parent->path . $category->id . ',';
            $category->level = $parent->level + 1;
        } else {
            $category->path = ',' . $category->id . ',';
            $category->level = 1;
        }

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function updateCategory($id)
    {
        $category = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CategoryValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['icon'])) {
            $data['icon'] = $validator->checkIcon($post['icon']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($category->parent_id == 0) {
                if ($category->published == 0 && $post['published'] == 1) {
                    $this->enableChildCategories($category->id);
                } elseif ($category->published == 1 && $post['published'] == 0) {
                    $this->disableChildCategories($category->id);
                }
            }
        }

        $category->update($data);

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->findOrFail($id);

        $validator = new CategoryValidator();

        $validator->checkDeleteAbility($category);

        $category->deleted = 1;

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    public function restoreCategory($id)
    {
        $category = $this->findOrFail($id);

        $category->deleted = 0;

        $category->update();

        $this->updateCategoryStats($category);
        $this->rebuildCategoryCache($category);

        return $category;
    }

    protected function updateCategoryStats(CategoryModel $category)
    {
        $categoryRepo = new CategoryRepo();

        if ($category->parent_id > 0) {
            $category = $categoryRepo->findById($category->parent_id);
        }

        $childCount = $categoryRepo->countChildCategories($category->id);

        $category->child_count = $childCount;

        $category->update();
    }

    protected function rebuildCategoryCache(CategoryModel $category)
    {
        $cache = new CategoryCache();

        $cache->rebuild($category->id);

        $cache = new CategoryListCache();

        $cache->rebuild($category->type);

        $cache = new CategoryTreeListCache();

        $cache->rebuild($category->type);

        $cache = new CategoryAllListCache();

        $cache->rebuild($category->type);
    }

    protected function enableChildCategories($parentId)
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll(['parent_id' => $parentId]);

        if ($categories->count() == 0) {
            return;
        }

        foreach ($categories as $category) {
            $category->published = 1;
            $category->update();
        }
    }

    protected function disableChildCategories($parentId)
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll(['parent_id' => $parentId]);

        if ($categories->count() == 0) {
            return;
        }

        foreach ($categories as $category) {
            $category->published = 0;
            $category->update();
        }
    }

    protected function findOrFail($id)
    {
        $validator = new CategoryValidator();

        return $validator->checkCategory($id);
    }

}
