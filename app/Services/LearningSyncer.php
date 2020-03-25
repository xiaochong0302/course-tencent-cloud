<?php

namespace App\Services;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\Learning;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Traits\Client as ClientTrait;

class LearningSyncer extends Service
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
     * @param int $timeout
     */
    public function addItem(LearningModel $learning, $timeout = 10)
    {
        /**
         * 兼容秒和毫秒
         */
        if ($timeout > 1000) {
            $timeout = intval($timeout / 1000);
        }

        $itemKey = $this->getItemKey($learning->request_id);

        /**
         * @var LearningModel $cacheLearning
         */
        $cacheLearning = $this->cache->get($itemKey);

        if (!$cacheLearning) {

            $chapterRepo = new ChapterRepo();

            $chapter = $chapterRepo->findById($learning->chapter_id);

            $learning->course_id = $chapter->course_id;
            $learning->client_type = $this->getClientType();
            $learning->client_ip = $this->getClientIp();
            $learning->duration = $timeout;

            $this->cache->save($itemKey, $learning, $this->lifetime);

        } else {

            $cacheLearning->duration += $timeout;

            $this->cache->save($itemKey, $cacheLearning, $this->lifetime);
        }

        $syncKey = $this->getSyncKey();

        $this->redis->sAdd($syncKey, $learning->request_id);

        $this->redis->expire($syncKey, $this->lifetime);
    }

    public function getItemKey($id)
    {
        return "learning:{$id}";
    }

    public function getSyncKey()
    {
        return 'learning_sync';
    }

}
