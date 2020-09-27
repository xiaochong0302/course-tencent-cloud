<?php

namespace App\Services\Sync;

use App\Services\Service;

class CourseIndex extends Service
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($courseId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $courseId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_course_index';
    }

}
