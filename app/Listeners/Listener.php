<?php

namespace App\Listeners;

use App\Services\Service as AppService;
use Phalcon\Mvc\User\Plugin as UserPlugin;

class Listener extends UserPlugin
{

    public function getConfig()
    {
        $appService = new AppService();

        return $appService->getConfig();
    }


    public function getCache()
    {
        $appService = new AppService();

        return $appService->getCache();
    }

    public function getRedis()
    {
        $appService = new AppService();

        return $appService->getRedis();
    }

    public function getLogger($channel = null)
    {
        $appService = new AppService();

        $channel = $channel ?: 'listen';

        return $appService->getLogger($channel);
    }

    public function getSettings($section)
    {
        $appService = new AppService();

        return $appService->getSettings($section);
    }

}
