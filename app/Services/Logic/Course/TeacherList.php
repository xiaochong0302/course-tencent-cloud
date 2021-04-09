<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class TeacherList extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $cache = new CourseTeacherListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
