<?php

namespace App\Services\Logic\Help;

use App\Caches\HelpList as HelpListCache;
use App\Services\Logic\Service as LogicService;

class HelpList extends LogicService
{

    public function handle()
    {
        $cache = new HelpListCache();

        return $cache->get();
    }

}
