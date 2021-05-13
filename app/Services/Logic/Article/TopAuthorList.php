<?php

namespace App\Services\Logic\Article;

use App\Caches\TopAuthorList as TopAuthorListCache;
use App\Services\Logic\Service as LogicService;

class TopAuthorList extends LogicService
{

    public function handle()
    {
        $cache = new TopAuthorListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
