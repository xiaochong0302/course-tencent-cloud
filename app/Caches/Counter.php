<?php

namespace App\Caches;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Mvc\User\Component;

abstract class Counter extends Component
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();
    }

    /**
     * 获取缓存内容
     *
     * @param mixed $id
     * @return array
     */
    public function get($id = null)
    {
        $key = $this->getKey($id);

        $content = $this->redis->hGetAll($key);

        if (!$this->cache->exists($key)) {

            $content = $this->getContent($id);
            $lifetime = $this->getLifetime();

            $this->redis->hMSet($key, $content);
            $this->redis->expire($key, $lifetime);
        }

        return $content;
    }

    /**
     * 删除缓存内容
     *
     * @param mixed $id
     */
    public function delete($id = null)
    {
        $key = $this->getKey($id);

        $this->cache->delete($key);
    }

    /**
     * 重建缓存内容
     *
     * @param mixed $id
     */
    public function rebuild($id = null)
    {
        $this->delete($id);

        $this->get($id);
    }

    public function hGet($id, $hashKey)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hGet($key, $hashKey);
    }

    public function hDel($id, $hashKey)
    {
        $key = $this->getKey($id);

        return $this->redis->hDel($key, $hashKey);
    }

    public function hIncrBy($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        $this->redis->hIncrBy($key, $hashKey, $value);
    }

    public function hDecrBy($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        $this->redis->hIncrBy($key, $hashKey, 0 - $value);
    }

    /**
     * 获取缓存有效期
     *
     * @return int
     */
    abstract public function getLifetime();

    /**
     * 获取键值
     *
     * @param mixed $id
     * @return string
     */
    abstract public function getKey($id = null);

    /**
     * 获取原始内容
     *
     * @param mixed $id
     * @return mixed
     */
    abstract public function getContent($id = null);

}
