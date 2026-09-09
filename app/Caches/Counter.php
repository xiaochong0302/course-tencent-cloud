<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use Phalcon\Di\Injectable;
use Redis;

abstract class Counter extends Injectable
{

    /**
     * @var Redis
     */
    protected Redis $redis;

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function __construct()
    {
        $this->redis = $this->getDI()->getShared('redis');
    }

    public function get($id = null): mixed
    {
        $key = $this->getKey($id);

        $content = $this->redis->hGetAll($key);

        if (!$this->redis->exists($key)) {

            $content = $this->getContent($id);

            $this->redis->hMSet($key, $content);
            $this->redis->expire($key, $this->lifetime);
        }

        return $content;
    }

    public function delete($id = null): int
    {
        $key = $this->getKey($id);

        return $this->redis->del($key);
    }

    public function rebuild($id = null): mixed
    {
        $this->delete($id);

        return $this->get($id);
    }

    public function hGet(string|int $id, string $hashKey): false|string
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hGet($key, $hashKey);
    }

    public function hDel(string|int $id, string $hashKey): bool|int
    {
        $key = $this->getKey($id);

        return $this->redis->hDel($key, $hashKey);
    }

    public function hIncrBy(string|int $id, string $hashKey, int $value = 1): int
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hIncrBy($key, $hashKey, $value);
    }

    public function hDecrBy(string|int $id, string $hashKey, int $value = 1): int
    {
        $key = $this->getKey($id);

        if (!$this->redis->exists($key)) {
            $this->get($id);
        }

        return $this->redis->hIncrBy($key, $hashKey, 0 - $value);
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
