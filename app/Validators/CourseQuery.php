<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;

class CourseQuery extends Validator
{

    public function checkCategory($id)
    {
        $validator = new Category();

        return $validator->checkCategoryCache($id);
    }

    public function checkTag($id)
    {
        $validator = new Tag();

        return $validator->checkTagCache($id);
    }

    public function checkUser($id)
    {
        $validator = new User();

        return $validator->checkUserCache($id);
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
