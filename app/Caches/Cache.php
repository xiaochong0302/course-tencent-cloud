<?php

namespace App\Caches;

use Phalcon\Mvc\User\Component as UserComponent;

abstract class Cache extends UserComponent
{

    /**
     * @var \Phalcon\Cache\Backend
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');
    }

    /**
     * 获取缓存内容
     *
     * @param mixed $params
     * @return mixed
     */
    public function get($params = null)
    {
        $key = $this->getKey($params);
        $content = $this->cache->get($key);
        $lifetime = $this->getLifetime();

        if (!$content) {
            $content = $this->getContent($params);
            $this->cache->save($key, $content, $lifetime);
            $content = $this->cache->get($key);
        }

        return $content;
    }

    /**
     * 删除缓存内容
     *
     * @param mixed $params
     */
    public function delete($params = null)
    {
        $key = $this->getKey($params);
        $this->cache->delete($key);
    }

    /**
     * 获取缓存有效期
     *
     * @return integer
     */
    abstract protected function getLifetime();

    /**
     * 获取键值
     *
     * @param mixed $params
     * @return string
     */
    abstract protected function getKey($params = null);

    /**
     * 获取原始内容
     *
     * @param mixed $params
     * @return mixed
     */
    abstract protected function getContent($params = null);

}
