<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Services\Smser\Live as LiveSmser;
use Phalcon\Cli\Task;

class LiveNoticeConsumerTask extends Task
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    public function mainAction()
    {
        $hour = date('h');

        /**
         * 限定合理的时间范围
         */
        if ($hour < 7 || $hour > 23) {
            return;
        }

        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();

        $providerTask = new LiveNoticeProviderTask();

        $cacheKey = $providerTask->getCacheKey();

        $members = $this->redis->sMembers($cacheKey);

        if (!$members) return;

        $smser = new LiveSmser();

        $now = time();

        foreach ($members as $member) {

            list($chapterId, $userId, $startTime) = explode(':', $member);

            $remove = false;

            if ($now - $startTime < 3600) {
                $smser->handle($chapterId, $userId, $startTime);
                $remove = true;
            }

            if ($now > $startTime) {
                $remove = true;
            }

            if ($remove) {
                $this->redis->sRem($cacheKey, $member);
            }
        }
    }

}
