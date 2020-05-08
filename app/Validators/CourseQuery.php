<?php

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;

class CourseQuery extends Validator
{

    public function checkTopCategory($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('course_query.invalid_top_category');
        }

        return $category->id;
    }

    public function checkSubCategory($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('course_query.invalid_sub_category');
        }

        return $category->id;
    }

    public function checkLevel($level)
    {
        $types = CourseModel::levelTypes();

        if (!isset($types[$level])) {
            throw new BadRequestException('course_query.invalid_level');
        }

        return $level;
    }

    public function checkModel($model)
    {
        $types = CourseModel::modelTypes();

        if (!isset($types[$model])) {
            throw new BadRequestException('course_query.invalid_model');
        }

        return $model;
    }

    public function checkSort($sort)
    {
        $types = CourseModel::sortTypes();

        if (!isset($types[$sort])) {
            throw new BadRequestException('course_query.invalid_sort');
        }

        return $sort;
    }

}
