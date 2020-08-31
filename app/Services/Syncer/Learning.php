<?php

namespace App\Services\Syncer;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\Learning as LearningModel;
use App\Services\Service;
use App\Traits\Client as ClientTrait;

class Learning extends Service
{

    use ClientTrait;

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();
    }

    /**
     * @param LearningModel $learning
     * @param int $interval
     */
    public function addItem(LearningModel $learning, $interval = 10)
    {
        $itemKey = $this->getItemKey($learning->request_id);

        /**
         * @var LearningModel $cacheLearning
         */
        $cacheLearning = $this->cache->get($itemKey);

        if (!$cacheLearning) {

            $learning->client_type = $this->getClientType();
            $learning->client_ip = $this->getClientIp();
            $learning->duration = $interval;
            $learning->active_time = time();

            $this->cache->save($itemKey, $learning, $this->lifetime);

        } else {

            $cacheLearning->duration += $interval;
            $cacheLearning->position = $learning->position;
            $cacheLearning->active_time = time();

            $this->cache->save($itemKey, $cacheLearning, $this->lifetime);
        }

        $key = $this->getSyncKey();

        $this->redis->sAdd($key, $learning->request_id);

        if ($this->redis->sCard($key) == 1) {
            $this->redis->expire($key, $this->lifetime);
        }
    }

    public function getItemKey($id)
    {
        return "learning:{$id}";
    }

    public function getSyncKey()
    {
        return 'sync_learning';
    }

}
