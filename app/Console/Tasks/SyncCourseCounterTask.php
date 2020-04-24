<?php

namespace App\Console\Tasks;

use App\Caches\CourseCounter as CourseCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Course as CourseRepo;
use App\Services\Syncer\CourseCounter as CourseCounterSyncer;

class SyncCourseCounterTask extends Task
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

        $this->rebuild();
    }

    protected function rebuild()
    {
        $key = $this->getCacheKey();

        $courseIds = $this->redis->sRandMember($key, 100);

        if (!$courseIds) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) {
            return;
        }

        $cache = new CourseCounterCache();

        foreach ($courses as $course) {

            $counter = $cache->get($course->id);

            if ($counter) {

                $course->user_count = $counter['user_count'];
                $course->lesson_count = $counter['lesson_count'];
                $course->comment_count = $counter['comment_count'];
                $course->consult_count = $counter['consult_count'];
                $course->review_count = $counter['review_count'];
                $course->favorite_count = $counter['favorite_count'];

                $course->update();
            }
        }

        $this->redis->sRem($key, ...$courseIds);
    }

    protected function getCacheKey()
    {
        $syncer = new CourseCounterSyncer();

        return $syncer->getSyncKey();
    }

}
