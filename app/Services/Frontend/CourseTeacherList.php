<?php

namespace App\Services\Frontend;

use App\Caches\CourseTeacherList as CourseTeacherListCache;

class CourseTeacherList extends Service
{

    use CourseTrait;

    public function getTeachers($id)
    {
        $course = $this->checkCourse($id);

        $ctListCache = new CourseTeacherListCache();

        $teachers = $ctListCache->get($course->id);

        if (!$teachers) return [];

        $imgBaseUrl = kg_img_base_url();

        foreach ($teachers as &$teacher) {
            $teacher['avatar'] = $imgBaseUrl . $teacher['avatar'];
        }

        return $teachers;
    }

}
