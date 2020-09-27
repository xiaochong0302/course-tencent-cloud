<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class TeacherList extends Service
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
