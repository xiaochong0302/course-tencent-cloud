<?php

namespace App\Listeners;

use App\Caches\Config as ConfigCache;
use App\Library\Logger as AppLogger;

class Listener extends \Phalcon\Mvc\User\Plugin
{

    /**
     * 获取Logger
     *
     * @param mixed $channel
     * @return \Phalcon\Logger\Adapter\File
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
     * @return \stdClass
     */
    public function getSectionConfig($section)
    {
        $configCache = new ConfigCache();

        $result = $configCache->getSectionConfig($section);

        return $result;
    }

}
