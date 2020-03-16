<?php

namespace App\Console\Tasks;

use App\Caches\Course as CourseCache;
use App\Caches\CourseCounter as CourseCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\CourseCacheSyncer;
use Phalcon\Mvc\Model\Resultset;

class RebuildCourseCacheTask extends Task
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

        /**
         * @var Resultset|CourseModel[] $courses
         */
        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) {
            return;
        }

        $courseCache = new CourseCache();
        $counterCache = new CourseCounterCache();

        foreach ($courses as $course) {

            $course->user_count = $courseRepo->countUsers($course->id);
            $course->comment_count = $courseRepo->countComments($course->id);
            $course->review_count = $courseRepo->countReviews($course->id);
            $course->favorite_count = $courseRepo->countFavorites($course->id);

            $course->update();

            $courseCache->rebuild($course->id);
            $counterCache->rebuild($course->id);
        }

        $this->redis->sRem($key, ...$courseIds);
    }

    protected function getCacheKey()
    {
        $syncer = new CourseCacheSyncer();

        return $syncer->getCacheKey();
    }

}
