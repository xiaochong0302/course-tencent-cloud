<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class TeacherList extends Service
{

    use CourseTrait;

    public function getTeachers($id)
    {
        $course = $this->checkCourse($id);

        $cache = new CourseTeacherListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
