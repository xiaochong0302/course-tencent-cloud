<?php

namespace App\Services\Frontend\Help;

use App\Caches\HelpList as HelpListCache;
use App\Services\Frontend\Service as FrontendService;

class HelpList extends FrontendService
{

    public function handle()
    {
        $cache = new HelpListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
