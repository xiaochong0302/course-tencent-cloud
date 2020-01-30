<?php

namespace App\Caches;

abstract class Counter extends \Phalcon\Mvc\User\Component
{

    /**
     * @var \App\Library\Cache\Backend\Redis
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

            /**
             * 原始内容为空，设置较短的生存时间，简单防止穿透
             */
            if (!$content) {

                $lifetime = 5 * 60;

                $content = ['default' => 0];
            }

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

    public function increment($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

        $this->redis->hIncrBy($key, $hashKey, $value);
    }

    public function decrement($id, $hashKey, $value = 1)
    {
        $key = $this->getKey($id);

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
