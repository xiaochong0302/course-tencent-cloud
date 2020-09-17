<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class RelatedList extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $cache = new CourseRelatedListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
