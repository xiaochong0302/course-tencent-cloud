<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;

class CourseQuery extends Validator
{

    public function checkCategory(int $id): CategoryModel
    {
        $validator = new Category();

        return $validator->checkCategoryCache($id);
    }

    public function checkUser(int $id): UserModel
    {
        $validator = new User();

        return $validator->checkUserCache($id);
    }

    public function checkLevel(int $level): int
    {
        $types = CourseModel::levelTypes();

        if (!isset($types[$level])) {
            throw new BadRequestException('course_query.invalid_level');
        }

        return $level;
    }

    public function checkModel(int $model): int
    {
        $types = CourseModel::modelTypes();

        if (!isset($types[$model])) {
            throw new BadRequestException('course_query.invalid_model');
        }

        return $model;
    }

    public function checkSort(string $sort): string
    {
        $types = CourseModel::sortTypes();

        if (!isset($types[$sort])) {
            throw new BadRequestException('course_query.invalid_sort');
        }

        return $sort;
    }

}
