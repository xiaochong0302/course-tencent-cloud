<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\CourseUser as CourseUserModel;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class LiveNoticeProviderTask extends Task
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

        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();

        $tasks = $this->findTasks();

        if ($tasks->count() == 0) {
            return;
        }

        $values = [];

        foreach ($tasks as $task) {
            $items = [$task->chapter_id, $task->user_id, $task->start_time];
            $values[] = implode(':', $items);
        }

        $key = $this->getCacheKey();

        $lifetime = $this->getLifetime();

        $this->redis->sAdd($key, ...$values);

        $this->redis->expire($key, $lifetime);
    }

    /**
     * @return ResultsetInterface|Resultset
     */
    protected function findTasks()
    {
        $beginTime = strtotime('today');

        $endTime = strtotime('tomorrow');

        /**
         * 过滤付费和导入用户，减少发送量
         */
        $sourceTypes = [
            CourseUserModel::SOURCE_CHARGE,
            CourseUserModel::SOURCE_IMPORT,
        ];

        $rows = $this->modelsManager->createBuilder()
            ->columns(['cu.course_id', 'cu.user_id', 'cl.chapter_id', 'cl.start_time'])
            ->addFrom(ChapterLiveModel::class, 'cl')
            ->join(CourseUserModel::class, 'cl.course_id = cu.course_id', 'cu')
            ->inWhere('cu.source_type', $sourceTypes)
            ->betweenWhere('start_time', $beginTime, $endTime)
            ->getQuery()->execute();

        return $rows;
    }

    public function getLifetime()
    {
        $tomorrow = strtotime('tomorrow');

        $lifetime = $tomorrow - time();

        return $lifetime;
    }

    public function getCacheKey()
    {
        return 'live_notice';
    }

}
