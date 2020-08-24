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

        $removeList = [];

        foreach ($members as $member) {

            list($chapterId, $userId, $startTime) = explode(':', $member);

            if ($now - $startTime < 3600) {
                $smser->handle($chapterId, $userId, $startTime);
                $removeList[] = $member;
            }

            if ($now > $startTime) {
                $removeList[] = $member;
            }
        }

        if (count($removeList) > 0) {
            $this->redis->sRem($cacheKey, ...$removeList);
        }
    }

}
