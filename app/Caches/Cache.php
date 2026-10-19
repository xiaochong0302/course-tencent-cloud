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
     * 空值哨兵，防止缓存穿透
     *
     * @var string
     */
    public const string NULL_SENTINEL = '__KG_NULL_CACHE__';

    /**
     * @var CacheInterface
     */
    protected CacheInterface $cache;

    /**
     * @var int
     */
    protected int $lifetime;

    /**
     * 空值缓存生命周期，远小于正常 lifetime，兼顾防穿透与数据可见性
     *
     * @var int
     */
    protected int $nullLifetime = 60;

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

        $content = $this->cache->get($key);

        if ($content === self::NULL_SENTINEL) {
            return null;
        }

        if ($content !== null) {
            return $content;
        }

        $content = $this->getContent($id);

        if ($content === null) {
            $this->cache->set($key, self::NULL_SENTINEL, $this->nullLifetime);
        } else {
            $this->cache->set($key, $content, $this->lifetime);
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
