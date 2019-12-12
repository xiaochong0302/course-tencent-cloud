<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Repos\Category as CategoryRepo;

class CourseQuery extends Validator
{

    public function checkCourseId($courseId)
    {
        $value = $this->filter->sanitize($courseId, ['trim', 'int']);

        if ($value > 0) {
            return $value;
        }

        return false;
    }

    public function checkUserId($userId)
    {
        $value = $this->filter->sanitize($userId, ['trim', 'int']);

        if ($value > 0) {
            return $value;
        }

        return false;
    }

    public function checkCategoryId($categoryId)
    {
        $value = $this->filter->sanitize($categoryId, ['trim', 'int']);

        if ($value <= 0) {
            return false;
        }

        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($value);

        if (!$category) {
            return false;
        }

        return $category->id;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);
        
        if (!empty($value)) {
            return $value;
        }
        
        return false;
    }

    public function checkLevel($level)
    {
        $value = $this->filter->sanitize($level, ['trim', 'int']);

        $scopes = [
            CourseModel::LEVEL_ENTRY,
            CourseModel::LEVEL_JUNIOR,
            CourseModel::LEVEL_MIDDLE,
            CourseModel::LEVEL_SENIOR,
        ];

        if (in_array($value, $scopes)) {
            return $value;
        }

        return false;
    }

    public function checkPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0) {
            throw new BadRequestException('无效的价格');
        }

        return $value;
    }

    public function checkStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        $scopes = [
            CourseModel::LEVEL_ENTRY,
            CourseModel::LEVEL_JUNIOR,
            CourseModel::LEVEL_MIDDLE,
            CourseModel::LEVEL_SENIOR,
        ];

        if (in_array($value, $scopes)) {
            return $value;
        }

        return false;
    }

    public function checkSort($sort)
    {
        switch ($sort) {
            case 'rating':
                $orderBy = 'rating DESC';
                break;
            case 'score':
                $orderBy = 'score DESC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        return $orderBy;
    }

}
