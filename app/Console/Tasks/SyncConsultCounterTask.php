<?php

namespace App\Console\Tasks;

use App\Caches\ConsultCounter as ConsultCounterCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Repos\Consult as ConsultRepo;
use App\Services\Syncer\ConsultCounter as ConsultCounterSyncer;

class SyncConsultCounterTask extends Task
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

        $consultIds = $this->redis->sRandMember($key, 500);

        if (!$consultIds) return;

        $consultRepo = new ConsultRepo();

        $consults = $consultRepo->findByIds($consultIds);

        if ($consults->count() == 0) {
            return;
        }

        $cache = new ConsultCounterCache();

        foreach ($consults as $consult) {

            $counter = $cache->get($consult->id);

            if ($counter) {

                $consult->agree_count = $counter['agree_count'];
                $consult->oppose_count = $counter['oppose_count'];

                $consult->update();
            }
        }

        $this->redis->sRem($key, ...$consultIds);
    }

    protected function getCacheKey()
    {
        $syncer = new ConsultCounterSyncer();

        return $syncer->getSyncKey();
    }

}
