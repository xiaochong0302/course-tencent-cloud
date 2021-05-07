<?php

namespace App\Services\Logic\Question;

use App\Caches\TopAnswererList as TopAnswererListCache;
use App\Services\Logic\Service as LogicService;

class TopAnswererList extends LogicService
{

    public function handle()
    {
        $cache = new TopAnswererListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
