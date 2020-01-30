<?php

namespace App\Services\Frontend;

use App\Caches\CourseRelatedList as CourseRelatedListCache;

class CourseRelated extends Service
{

    use CourseTrait;

    public function getRelated($id)
    {
        $course = $this->checkCourseCache($id);

        $crListCache = new CourseRelatedListCache();

        $relatedCourses = $crListCache->get($course->id);

        if (!$relatedCourses) {
            return [];
        }

        $imgBaseUrl = kg_img_base_url();

        foreach ($relatedCourses as &$course) {
            $course['cover'] = $imgBaseUrl . $course['cover'];
        }

        return $relatedCourses;
    }

}
