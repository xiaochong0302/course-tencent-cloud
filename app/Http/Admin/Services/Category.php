<?php

namespace App\Http\Admin\Services;

use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;
use App\Validators\Category as CategoryValidator;

class Category extends Service
{

    public function getCategory($id)
    {
        $category = $this->findOrFail($id);

        return $category;
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

    public function getTopCategories()
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll([
            'parent_id' => 0,
            'deleted' => 0,
        ]);

        return $categories;
    }

    public function getChildCategories($parentId)
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ]);

        return $categories;
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

        $data['name'] = $validator->checkName($post['name']);
        $data['priority'] = $validator->checkPriority($post['priority']);
        $data['published'] = $validator->checkPublishStatus($post['published']);

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

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $category->update($data);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->findOrFail($id);

        if ($category->deleted == 1) {
            return false;
        }

        $category->deleted = 1;

        $category->update();

        return $category;
    }

    public function restoreCategory($id)
    {
        $category = $this->findOrFail($id);

        if ($category->deleted == 0) {
            return false;
        }

        $category->deleted = 0;

        $category->update();

        return $category;
    }

    protected function findOrFail($id)
    {
        $validator = new CategoryValidator();

        $result = $validator->checkCategory($id);

        return $result;
    }

}
