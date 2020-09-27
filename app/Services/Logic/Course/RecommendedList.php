<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseRecommendedList as CourseRecommendedListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

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
