<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class RelatedList extends LogicService
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
