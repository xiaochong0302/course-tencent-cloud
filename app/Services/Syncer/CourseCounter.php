<?php

namespace App\Services\Syncer;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Services\Service;

class CourseCounter extends Service
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();
    }

    public function addItem($courseId)
    {
        $key = $this->getSyncKey();

        $this->redis->sAdd($key, $courseId);

        $this->redis->expire($key, $this->lifetime);
    }

    public function getSyncKey()
    {
        return 'course_counter_sync';
    }

}
