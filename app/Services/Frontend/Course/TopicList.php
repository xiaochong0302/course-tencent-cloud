<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseTopicList as CourseTopicListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class TopicList extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $cache = new CourseTopicListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
