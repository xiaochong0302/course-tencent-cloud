<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Course as CourseValidator;

trait CourseTrait
{

    public function checkCourse($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    public function checkCourseCache($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourseCache($id);
    }

}
