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

        $teachers = $listCache->get($course->id);

        if (!$teachers) return [];

        $imgBaseUrl = kg_img_base_url();

        foreach ($teachers as &$teacher) {
            $teacher['avatar'] = $imgBaseUrl . $teacher['avatar'];
        }

        return $teachers;
    }

}
