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

        $listCache = new CourseTeacherListCache();

        return $listCache->get($course->id);
    }

}
