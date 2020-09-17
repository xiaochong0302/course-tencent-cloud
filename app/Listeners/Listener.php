<?php

namespace App\Listeners;

use App\Caches\Setting as SectionConfigCache;
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
    public function getSettings($section)
    {
        $cache = new SectionConfigCache();

        return $cache->get($section);
    }

}
