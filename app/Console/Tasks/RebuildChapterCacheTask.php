<?php

namespace App\Console\Tasks;

use App\Caches\Chapter as ChapterCache;
use App\Caches\ChapterCounter as ChapterCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterCacheSyncer;
use Phalcon\Mvc\Model\Resultset;

class RebuildChapterCacheTask extends Task
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

        $chapterIds = $this->redis->sRandMember($key, 500);

        if (!$chapterIds) return;

        $chapterRepo = new ChapterRepo();

        /**
         * @var Resultset $chapters
         */
        $chapters = $chapterRepo->findByIds($chapterIds);

        if ($chapters->count() == 0) {
            return;
        }

        $chapterCache = new ChapterCache();
        $counterCache = new ChapterCounterCache();

        foreach ($chapters as $chapter) {
            $chapter->user_count = $chapterRepo->countUsers($chapter->id);
            $chapter->comment_count = $chapterRepo->countComments($chapter->id);
            $chapter->like_count = $chapterRepo->countLikes($chapter->id);
            $chapter->update();

            $chapterCache->rebuild($chapter->id);
            $counterCache->rebuild($chapter->id);
        }
    }

    protected function getCacheKey()
    {
        $syncer = new ChapterCacheSyncer();

        return $syncer->getCacheKey();
    }

}
