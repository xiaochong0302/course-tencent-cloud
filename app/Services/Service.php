<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Caches\Setting as SettingCache;
use App\Library\Cache\Backend\Redis as RedisCache;
use App\Library\Logger as AppLogger;
use App\Traits\Auth as AuthTrait;
use Phalcon\Config as PhConfig;
use Phalcon\Logger\Adapter\File as PhLogger;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    use AuthTrait;

    /**
     * @return PhConfig
     */
    public function getConfig()
    {
        return $this->getDI()->getShared('config');
    }

    /**
     * @return RedisCache
     */
    public function getCache()
    {
        return $this->getDI()->getShared('cache');
    }

    /**
     * @return \Redis
     */
    public function getRedis()
    {
        return $this->getCache()->getRedis();
    }

    /**
     * 获取Logger
     *
     * @param string $channel
     * @return PhLogger
     */
    public function getLogger($channel = null)
    {
        $logger = new AppLogger();

        $channel = $channel ?: 'common';

        return $logger->getInstance($channel);
    }

    /**
     * 获取某组配置项
     *
     * @param string $section
     * @return array
     */
    public function getSettings($section)
    {
        $cache = new SettingCache();

        return $cache->get($section);
    }

}
