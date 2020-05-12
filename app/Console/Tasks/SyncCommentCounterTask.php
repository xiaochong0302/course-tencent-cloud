<?php

namespace App\Console\Tasks;

use App\Caches\CommentCounter as CommentCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Comment as CommentRepo;
use App\Services\Syncer\CommentCounter as CommentCounterSyncer;

class SyncCommentCounterTask extends Task
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

        $commentIds = $this->redis->sRandMember($key, 500);

        if (!$commentIds) return;

        $commentRepo = new CommentRepo();

        $comments = $commentRepo->findByIds($commentIds);

        if ($comments->count() == 0) {
            return;
        }

        $counterCache = new CommentCounterCache();

        $hour = date('H');

        foreach ($comments as $comment) {

            if ($hour % 3 == 0) {

                $comment->reply_count = $commentRepo->countReplies($comment->id);
                $comment->agree_count = $commentRepo->countAgrees($comment->id);
                $comment->oppose_count = $commentRepo->countOpposes($comment->id);

                $comment->update();

                $counterCache->rebuild($comment->id);

            } else {

                $counter = $counterCache->get($comment->id);

                if ($counter) {

                    $comment->reply_count = $counter['reply_count'];
                    $comment->agree_count = $counter['agree_count'];
                    $comment->oppose_count = $counter['oppose_count'];

                    $comment->update();
                }
            }
        }

        $this->redis->sRem($key, ...$commentIds);
    }

    protected function getCacheKey()
    {
        $syncer = new CommentCounterSyncer();

        return $syncer->getSyncKey();
    }

}
