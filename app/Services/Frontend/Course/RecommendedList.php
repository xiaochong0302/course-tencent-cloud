<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseRecommendedList as CourseRecommendedListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class RecommendedList extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $cache = new CourseRecommendedListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
