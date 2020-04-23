<?php

namespace App\Console\Tasks;

use App\Caches\ReviewCounter as ReviewCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Review as ReviewRepo;
use App\Services\ReviewCounterSyncer;

class SyncReviewCounterTask extends Task
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

        $reviewIds = $this->redis->sRandMember($key, 500);

        if (!$reviewIds) return;

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($reviewIds);

        if ($reviews->count() == 0) {
            return;
        }

        $cache = new ReviewCounterCache();

        foreach ($reviews as $review) {

            $counter = $cache->get($review->id);

            if ($counter) {

                $review->agree_count = $counter['agree_count'];
                $review->oppose_count = $counter['oppose_count'];

                $review->update();
            }
        }

        $this->redis->sRem($key, ...$reviewIds);
    }

    protected function getCacheKey()
    {
        $syncer = new ReviewCounterSyncer();

        return $syncer->getSyncKey();
    }

}
