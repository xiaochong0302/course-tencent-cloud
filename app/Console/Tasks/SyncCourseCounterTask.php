<?php

namespace App\Console\Tasks;

use App\Caches\Course as CourseCache;
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

        $counterCache = new CourseCounterCache();

        $courseCache = new CourseCache();

        $allowRecount = $this->allowRecount();

        foreach ($courses as $course) {

            if ($allowRecount) {

                $course->user_count = $courseRepo->countUsers($course->id);
                $course->comment_count = $courseRepo->countComments($course->id);
                $course->consult_count = $courseRepo->countConsults($course->id);
                $course->review_count = $courseRepo->countReviews($course->id);
                $course->favorite_count = $courseRepo->countFavorites($course->id);
                $course->update();

                $counterCache->rebuild($course->id);
                $courseCache->rebuild($course->id);

            } else {

                $counter = $counterCache->get($course->id);

                if ($counter) {
                    $course->user_count = $counter['user_count'];
                    $course->comment_count = $counter['comment_count'];
                    $course->consult_count = $counter['consult_count'];
                    $course->review_count = $counter['review_count'];
                    $course->favorite_count = $counter['favorite_count'];
                    $course->update();
                }
            }
        }

        $this->redis->sRem($key, ...$courseIds);
    }

    protected function getCacheKey()
    {
        $syncer = new CourseCounterSyncer();

        return $syncer->getSyncKey();
    }

    protected function allowRecount()
    {
        return date('H') % 2 == 0;
    }

}
