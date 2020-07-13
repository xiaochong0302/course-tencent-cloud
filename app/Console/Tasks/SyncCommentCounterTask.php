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

        $recount = $this->checkEnableRecount();

        foreach ($comments as $comment) {

            if ($recount && $hour % 3 == 0) {

                $comment->reply_count = $commentRepo->countReplies($comment->id);
                $comment->like_count = $commentRepo->countLikes($comment->id);
                $comment->update();

                $counterCache->rebuild($comment->id);

            } else {

                $counter = $counterCache->get($comment->id);

                if ($counter) {
                    $comment->reply_count = $counter['reply_count'];
                    $comment->like_count = $counter['like_count'];
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

    protected function checkEnableRecount()
    {
        $config = $this->getDI()->get('config');

        return $config->syncer->recount_comment ?? false;
    }

}
