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

        return $listCache->get($course->id);
    }

}
