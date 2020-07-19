<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Cli\Task;

class CleanSessionTask extends Task
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

        $keys = $this->querySessionKeys(10000);

        if (count($keys) == 0) return;

        $config = $this->getDI()->get('config');

        $lifetime = $config->session->lifetime;

        foreach ($keys as $key) {
            $ttl = $this->redis->ttl($key);
            $content = $this->redis->get($key);
            if (empty($content) && $ttl < $lifetime * 0.5) {
                $this->redis->del($key);
            }
        }
    }

    /**
     * 查找待清理会话
     *
     * @param int $limit
     * @return array
     */
    protected function querySessionKeys($limit)
    {
        return $this->cache->queryKeys('_PHCR', $limit);
    }

}
