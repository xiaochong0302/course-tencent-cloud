<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\ImGroup as GroupRepo;
use App\Services\Search\GroupDocument;
use App\Services\Search\GroupSearcher;
use App\Services\Syncer\GroupIndex as GroupIndexSyncer;

class SyncGroupIndexTask extends Task
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

        $groupIds = $this->redis->sRandMember($key, 1000);

        if (!$groupIds) return;

        $groupRepo = new GroupRepo();

        $groups = $groupRepo->findByIds($groupIds);

        if ($groups->count() == 0) {
            return;
        }

        $document = new GroupDocument();

        $handler = new GroupSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($groups as $group) {

            $doc = $document->setDocument($group);

            if ($group->published == 1) {
                $index->update($doc);
            } else {
                $index->del($group->id);
            }
        }

        $index->closeBuffer();

        $this->redis->sRem($key, ...$groupIds);
    }

    protected function getSyncKey()
    {
        $syncer = new GroupIndexSyncer();

        return $syncer->getSyncKey();
    }

}
