<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\User as UserRepo;
use App\Services\Search\UserDocument;
use App\Services\Search\UserSearcher;
use App\Services\Syncer\UserIndex as UserIndexSyncer;

class SyncUserIndexTask extends Task
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
        $key = $this->getSyncKey();

        $userIds = $this->redis->sRandMember($key, 1000);

        if (!$userIds) return;

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($userIds);

        if ($users->count() == 0) {
            return;
        }

        $document = new UserDocument();

        $handler = new UserSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($users as $user) {

            $doc = $document->setDocument($user);

            if ($user->deleted == 0) {
                $index->update($doc);
            } else {
                $index->del($user->id);
            }
        }

        $index->closeBuffer();

        $this->redis->sRem($key, ...$userIds);
    }

    protected function getSyncKey()
    {
        $syncer = new UserIndexSyncer();

        return $syncer->getSyncKey();
    }

}
