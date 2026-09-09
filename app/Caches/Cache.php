<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use Phalcon\Cache\CacheInterface;
use Phalcon\Di\Injectable;

abstract class Cache extends Injectable
{

    /**
     * @var CacheInterface
     */
    protected CacheInterface $cache;

    /**
     * @var int
     */
    protected int $lifetime;

    public function __construct()
    {
        $this->cache = $this->getDI()->getShared('cache');
    }

    /**
     * 获取缓存内容
     */
    public function get($id = null): mixed
    {
        $key = $this->getKey($id);

        if (!$this->cache->has($key)) {
            $content = $this->getContent($id);
            $this->cache->set($key, $content, $this->lifetime);
        } else {
            $content = $this->cache->get($key);
        }

        return $content;
    }

    /**
     * 删除缓存内容
     */
    public function delete($id = null): bool
    {
        $key = $this->getKey($id);

        return $this->cache->delete($key);
    }

    /**
     * 重建缓存内容
     */
    public function rebuild($id = null): mixed
    {
        $this->delete($id);

        return $this->get($id);
    }

    /**
     * 获取键值
     */
    abstract public function getKey($id = null): string;

    /**
     * 获取原始内容
     */
    abstract public function getContent($id = null): mixed;

}
