<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
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
        $this->checkId($id);

        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkCategory($id)
    {
        $this->checkId($id);

        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new BadRequestException('category.not_found');
        }

        return $category;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxCategoryIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('category.not_found');
        }
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

    public function checkIcon($icon)
    {
        $value = $this->filter->sanitize($icon, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('category.invalid_icon');
        }

        return kg_cos_img_style_trim($value);
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

    public function checkDeleteAbility(CategoryModel $category)
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
