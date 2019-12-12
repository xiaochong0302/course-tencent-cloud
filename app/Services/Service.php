<?php

namespace App\Services;

use App\Caches\Config as ConfigCache;
use App\Library\Logger as AppLogger;

class Service extends \Phalcon\Mvc\User\Component
{

    /**
     * 获取Logger
     *
     * @param string $channel
     * @return \Phalcon\Logger\Adapter\File
     */
    public function getLogger($channel = null)
    {
        $logger = new AppLogger();

        $channel = $channel ?: 'service';

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
