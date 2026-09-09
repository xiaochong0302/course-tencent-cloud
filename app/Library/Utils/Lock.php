<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

use Phalcon\Di\Di;
use Redis;

class Lock
{

    public static function addLock(string $itemId, int $expire = 10): string|false
    {
        if (empty($itemId) || $expire <= 0) {
            return false;
        }

        $redis = self::getRedis();

        $keyName = self::getLockKey($itemId);

        $lockId = sprintf('%s-%s-%s', uniqid(), rand(100, 999), rand(100, 999));

        $result = $redis->set($keyName, $lockId, ['nx', 'ex' => $expire]);

        return $result ? $lockId : false;
    }

    public static function releaseLock(string $itemId, string $lockId): bool
    {
        if (empty($itemId) || empty($lockId)) {
            return false;
        }

        $redis = self::getRedis();

        $keyName = self::getLockKey($itemId);

        $redis->watch($keyName);

        /**
         * 监听key防止被修改或删除，提交事务后会自动取消监控，其他情况需手动解除监控
         */
        if ($lockId == $redis->get($keyName)) {
            $redis->multi()->del($keyName)->exec();
            return true;
        }

        $redis->unwatch();

        return false;
    }

    public static function getRedis(): Redis
    {
        return Di::getDefault()->getShared('redis');
    }

    public static function getLockKey(string $id): string
    {
        return "lock-{$id}";
    }

}
