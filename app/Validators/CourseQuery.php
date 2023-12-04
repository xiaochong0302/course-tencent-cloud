<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Caches\Tag as TagCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;

class CourseQuery extends Validator
{

    public function checkCategory($id)
    {
        $validator = new Category();

        $category = $validator->checkCategoryCache($id);

        if (!$category) {
            throw new BadRequestException('course_query.invalid_category');
        }

        if ($category->type != CategoryModel::TYPE_COURSE) {
            throw new BadRequestException('course_query.invalid_category');
        }

        return $category;
    }

    public function checkTag($id)
    {
        $validator = new Tag();

        $tag = $validator->checkTagCache($id);

        if (!$tag) {
            throw new BadRequestException('course_query.invalid_tag');
        }

        return $tag;
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
