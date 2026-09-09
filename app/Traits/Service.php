<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Caches\Setting as SettingCache;
use App\Library\Logger as AppLogger;
use App\Repos\Setting as SettingRepo;
use Phalcon\Cache\CacheInterface;
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Events\Manager as PhEventsManager;
use Phalcon\Logger\Logger;
use Redis;

trait Service
{

    /**
     * Cli中的 getEventsManager()方法获取的对象为 null，需要从容器中获取
     */
    protected function getPhEventsManager(): PhEventsManager
    {
        return $this->getDI()->getShared('eventsManager');
    }

    /**
     * 获取Config
     *
     * @return Config
     */
    protected function getConfig(): Config
    {
        return Di::getDefault()->getShared('config');
    }

    /**
     * 获取Cache
     *
     * @return CacheInterface
     */
    protected function getCache(): CacheInterface
    {
        return Di::getDefault()->getShared('cache');
    }

    /**
     * 获取Redis
     *
     * @return Redis
     */
    protected function getRedis(): Redis
    {
        return Di::getDefault()->getShared('redis');
    }

    /**
     * 获取Logger
     *
     * @param string $channel
     * @return Logger
     */
    protected function getLogger(string $channel = 'common'): Logger
    {
        $logger = new AppLogger();

        return $logger->getInstance($channel);
    }

    /**
     * 获取某组配置项
     *
     * @param string $section
     * @param bool $cache
     * @return array
     */
    protected function getSettings(string $section, bool $cache = true): array
    {
        if ($cache) {
            $cache = new SettingCache();
            return $cache->get($section);
        }

        $settingRepo = new SettingRepo();

        $result = $settingRepo->findBySection($section);

        return $result ? $result->toArray() : [];
    }

}
