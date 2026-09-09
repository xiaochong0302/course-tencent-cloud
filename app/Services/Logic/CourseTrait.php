<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Course as CourseModel;
use App\Validators\Course as CourseValidator;

trait CourseTrait
{

    protected function checkCourse(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    protected function checkCourseCache(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourseCache($id);
    }

}
