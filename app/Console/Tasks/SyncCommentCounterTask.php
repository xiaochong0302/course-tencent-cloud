<?php

namespace App\Console\Tasks;

use App\Caches\CommentCounter as CommentCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Comment as CommentRepo;
use App\Services\CommentCounterSyncer;

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

        $cache = new CommentCounterCache();

        foreach ($comments as $comment) {

            $counter = $cache->get($comment->id);

            if ($counter) {

                $comment->reply_count = $counter['reply_count'];
                $comment->agree_count = $counter['agree_count'];
                $comment->oppose_count = $counter['oppose_count'];

                $comment->update();
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
