<?php

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;

class Category extends Validator
{

    /**
     * @param int $id
     * @return CategoryModel
     * @throws BadRequestException
     */
    public function checkCategoryCache($id)
    {
        $id = intval($id);

        $maxCategoryIdCache = new MaxCategoryIdCache();

        $maxCategoryId = $maxCategoryIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxCategoryId) {
            throw new BadRequestException('category.not_found');
        }

        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkParent($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new BadRequestException('category.parent_not_found');
        }

        return $category;
    }

    public function checkType($type)
    {
        $list = CategoryModel::types();

        if (!array_key_exists($type, $list)) {
            throw new BadRequestException('category.invalid_type');
        }

        return $type;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('category.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('category.name_too_long');
        }

        return $value;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('category.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('category.invalid_publish_status');
        }

        return $status;
    }

    public function checkDeleteAbility($category)
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->findAll([
            'parent_id' => $category->id,
            'deleted' => 0,
        ]);

        if ($categories->count() > 0) {
            throw new BadRequestException('category.has_child_node');
        }
    }

}
