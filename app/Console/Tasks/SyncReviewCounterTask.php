<?php

namespace App\Console\Tasks;

use App\Caches\ReviewCounter as ReviewCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Review as ReviewRepo;
use App\Services\Syncer\ReviewCounter as ReviewCounterSyncer;

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

        $counterCache = new ReviewCounterCache();

        $allowRecount = $this->allowRecount();

        foreach ($reviews as $review) {

            if ($allowRecount) {

                $review->like_count = $reviewRepo->countLikes($review->id);
                $review->update();

                $counterCache->rebuild($review->id);

            } else {

                $counter = $counterCache->get($review->id);

                if ($counter) {
                    $review->like_count = $counter['like_count'];
                    $review->update();
                }
            }
        }

        $this->redis->sRem($key, ...$reviewIds);
    }

    protected function getCacheKey()
    {
        $syncer = new ReviewCounterSyncer();

        return $syncer->getSyncKey();
    }

    protected function allowRecount()
    {
        return date('H') == 2;
    }

}
