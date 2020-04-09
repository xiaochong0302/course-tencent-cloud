<?php

namespace App\Services;

use App\Caches\SectionConfig as SectionConfigCache;
use App\Library\Logger as AppLogger;
use App\Traits\Auth as AuthTrait;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    use AuthTrait;

    /**
     * 获取Logger
     *
     * @param string $channel
     * @return FileLogger
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
    public function getSectionConfig($section)
    {
        $cache = new SectionConfigCache();

        return $cache->get($section);
    }

}
