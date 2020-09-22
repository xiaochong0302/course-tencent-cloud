<?php

namespace App\Listeners;

use App\Services\Service as AppService;
use Phalcon\Mvc\User\Plugin as UserPlugin;

class Listener extends UserPlugin
{

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
