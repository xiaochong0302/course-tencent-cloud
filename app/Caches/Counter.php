<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Di\Injectable;
use Redis;

abstract class Counter extends Injectable
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var Redis
     */
    protected $redis;

    public function __construct()
    {
        $this->cache = $this->getDI()->getShared('cache');

        $this->redis = $this->cache->getRedis();
    }

    public function get($id = null)
    {
        $key = $this->getKey($id);

        $content = $this->redis->hGetAll($key);

        if (!$this->redis->exists($key)) {

            $content = $this->getContent($id);
            $lifetime = $this->getLifetime();

            $this->redis->hMSet($key, $content);
            $this->redis->expire($key, $lifetime);
        }

        return $content;
    }

    public function delete($id = null)
    {
        $key = $this->getKey($id);

        $this->redis->del($key);
    }

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
