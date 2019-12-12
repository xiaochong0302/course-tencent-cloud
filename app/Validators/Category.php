<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Repos\Category as CategoryRepo;

class Category extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Category
     * @throws NotFoundException
     */
    public function checkCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            throw new NotFoundException('category.not_found');
        }

        return $category;
    }

    public function checkParent($parentId)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($parentId);

        if (!$category || $category->deleted == 1) {
            throw new BadRequestException('category.parent_not_found');
        }

        return $category;
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
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('category.invalid_publish_status');
        }

        return $value;
    }

}
