<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class CourseRelated extends Service
{

    use CourseTrait;

    public function getRelated($id)
    {
        $course = $this->checkCourse($id);

        $listCache = new CourseRelatedListCache();

        $courses = $listCache->get($course->id);

        if (!$courses) {
            return [];
        }

        $imgBaseUrl = kg_img_base_url();

        foreach ($courses as &$course) {
            $course['cover'] = $imgBaseUrl . $course['cover'];
        }

        return $courses;
    }

}
