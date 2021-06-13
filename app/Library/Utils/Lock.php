<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Utils;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Di;
use Phalcon\Text;

class Lock
{

    /**
     * @param string $itemId
     * @param int $expire
     * @return bool|string
     */
    public static function addLock($itemId, $expire = 600)
    {
        if (empty($itemId) || $expire <= 0) {
            return false;
        }

        /**
         * @var RedisCache $cache
         */
        $cache = Di::getDefault()->getShared('cache');

        $redis = $cache->getRedis();

        $lockId = Text::random(Text::RANDOM_ALNUM, 16);

        $keyName = self::getLockKey($itemId);

        $result = $redis->set($keyName, $lockId, ['nx', 'ex' => $expire]);

        return $result ? $lockId : false;
    }

    /**
     * @param string $itemId
     * @param string $lockId
     * @return bool
     */
    public static function releaseLock($itemId, $lockId)
    {
        if (empty($itemId) || empty($lockId)) {
            return false;
        }

        /**
         * @var RedisCache $cache
         */
        $cache = Di::getDefault()->getShared('cache');

        $redis = $cache->getRedis();

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

    public static function getLockKey($itemId)
    {
        return sprintf('_LOCK_:%s', $itemId);
    }

}