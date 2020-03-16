<?php

namespace App\Listeners;

use App\Caches\Config as ConfigCache;
use App\Library\Logger as AppLogger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\User\Plugin as UserPlugin;

class Listener extends UserPlugin
{

    /**
     * 获取Logger
     *
     * @param mixed $channel
     * @return FileLogger
     */
    public function getLogger($channel = null)
    {
        $logger = new AppLogger();

        $channel = $channel ?: 'listener';

        return $logger->getInstance($channel);
    }

    /**
     * 获取某组配置项
     *
     * @param string $section
     * @return array
     */
    public function getSectionConfig($section)
    {
        $configCache = new ConfigCache();

        $result = $configCache->getSectionConfig($section);

        return $result;
    }

}
