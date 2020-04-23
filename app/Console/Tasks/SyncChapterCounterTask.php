<?php

namespace App\Console\Tasks;

use App\Caches\ChapterCounter as ChapterCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterCounterSyncer;

class SyncChapterCounterTask extends Task
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

        $chapters = $chapterRepo->findByIds($chapterIds);

        if ($chapters->count() == 0) {
            return;
        }

        $cache = new ChapterCounterCache();

        foreach ($chapters as $chapter) {

            $counter = $cache->get($chapter->id);

            if ($counter) {

                $chapter->user_count = $counter['user_count'];
                $chapter->lesson_count = $counter['lesson_count'];
                $chapter->comment_count = $counter['comment_count'];
                $chapter->agree_count = $counter['agree_count'];
                $chapter->oppose_count = $counter['oppose_count'];

                $chapter->update();
            }
        }

        $this->redis->sRem($key, ...$chapterIds);
    }

    protected function getCacheKey()
    {
        $syncer = new ChapterCounterSyncer();

        return $syncer->getSyncKey();
    }

}
