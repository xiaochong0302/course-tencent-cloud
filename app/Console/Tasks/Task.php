<?php

namespace App\Console\Tasks;

use App\Services\Service as AppService;


class Task extends \Phalcon\Cli\Task
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

        return $appService->getLogger($channel);
    }

    public function getSettings($section)
    {
        $appService = new AppService();

        return $appService->getLogger($section);
    }

}
