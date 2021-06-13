<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use Phalcon\Cache\Backend\Redis as RedisCache;
use Phalcon\Di\Injectable;

abstract class Cache extends Injectable
{

    /**
     * @var RedisCache
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->getShared('cache');
    }

    /**
     * 获取缓存内容
     *
     * @param mixed $id
     * @return mixed
     */
    public function get($id = null)
    {
        $key = $this->getKey($id);

        if (!$this->cache->exists($key)) {

            $content = $this->getContent($id);

            $lifetime = $this->getLifetime();

            $this->cache->save($key, $content, $lifetime);

        } else {
            $content = $this->cache->get($key);
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
