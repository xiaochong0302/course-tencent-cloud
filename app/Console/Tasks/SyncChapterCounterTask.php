<?php

namespace App\Console\Tasks;

use App\Caches\Chapter as ChapterCache;
use App\Caches\ChapterCounter as ChapterCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Chapter as ChapterRepo;
use App\Services\Syncer\ChapterCounter as ChapterCounterSyncer;

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

        $counterCache = new ChapterCounterCache();

        $chapterCache = new ChapterCache();

        $hour = date('H');

        foreach ($chapters as $chapter) {

            if ($hour % 3 == 0) {

                $chapter->user_count = $chapterRepo->countUsers($chapter->id);
                $chapter->lesson_count = $chapterRepo->countLessons($chapter->id);
                $chapter->comment_count = $chapterRepo->countComments($chapter->id);
                $chapter->agree_count = $chapterRepo->countAgrees($chapter->id);
                $chapter->oppose_count = $chapterRepo->countOpposes($chapter->id);

                $chapter->update();

                $counterCache->rebuild($chapter->id);
                $chapterCache->rebuild($chapter->id);

            } else {

                $counter = $counterCache->get($chapter->id);

                if ($counter) {

                    $chapter->user_count = $counter['user_count'];
                    $chapter->lesson_count = $counter['lesson_count'];
                    $chapter->comment_count = $counter['comment_count'];
                    $chapter->agree_count = $counter['agree_count'];
                    $chapter->oppose_count = $counter['oppose_count'];

                    $chapter->update();

                    $chapterCache->rebuild($chapter->id);
                }
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
