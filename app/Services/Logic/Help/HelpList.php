<?php

namespace App\Services\Logic\Help;

use App\Caches\HelpList as HelpListCache;
use App\Services\Logic\Service;

class HelpList extends Service
{

    public function handle()
    {
        $cache = new HelpListCache();

        return $cache->get();
    }

}
