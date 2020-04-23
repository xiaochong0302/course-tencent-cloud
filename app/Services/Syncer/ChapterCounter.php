<?php

namespace App\Services\Syncer;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Services\Service;

class ChapterCounter extends Service
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

    public function addItem($chapterId)
    {
        $key = $this->getSyncKey();

        $this->redis->sAdd($key, $chapterId);

        $this->redis->expire($key, $this->lifetime);
    }

    public function getSyncKey()
    {
        return 'chapter_counter_sync';
    }

}
