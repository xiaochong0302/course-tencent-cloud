<?php

namespace App\Services\Frontend\Topic;

use App\Caches\TopicCourseList as TopicCourseListCache;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\TopicTrait;

class CourseList extends FrontendService
{

    use TopicTrait;

    public function handle($id)
    {
        $topic = $this->checkTopicCache($id);

        $cache = new TopicCourseListCache();

        $result = $cache->get($topic->id);

        return $result ?: [];
    }

}
