<?php

namespace App\Services;

class ChapterCacheSyncer extends Service
{

    /**
     * @var \App\Library\Cache\Backend\Redis
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    protected $lifetime = 86400;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();
    }

    public function addItem($courseId)
    {
        $key = $this->getCacheKey();

        $this->redis->sAdd($key, $courseId);

        $this->redis->expire($key, $this->lifetime);
    }

    public function getCacheKey()
    {
        return 'chapter_cache_sync';
    }

}
