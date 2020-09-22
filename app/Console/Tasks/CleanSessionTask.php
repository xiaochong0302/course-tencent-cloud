<?php

namespace App\Console\Tasks;

class CleanSessionTask extends Task
{

    public function mainAction()
    {
        $config = $this->getConfig();
        $redis = $this->getRedis();

        $redis->select($config->path('session.db'));

        $keys = $this->querySessionKeys(10000);

        if (count($keys) == 0) return;

        $lifetime = $config->path('session.lifetime');

        foreach ($keys as $key) {
            $ttl = $redis->ttl($key);
            $content = $redis->get($key);
            if (empty($content) && $ttl < $lifetime * 0.5) {
                $redis->del($key);
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
        $cache = $this->getCache();

        return $cache->queryKeys('_PHCR', $limit);
    }

}
