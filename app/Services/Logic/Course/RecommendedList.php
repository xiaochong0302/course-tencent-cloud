<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseRecommendedList as CourseRecommendedListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class RecommendedList extends LogicService
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
