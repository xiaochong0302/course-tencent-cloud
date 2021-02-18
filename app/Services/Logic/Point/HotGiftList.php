<?php

namespace App\Services\Logic\Point;

use App\Caches\PointHotGiftList;
use App\Services\Logic\Service;

class HotGiftList extends Service
{

    public function handle()
    {
        $cache = new PointHotGiftList();

        $cache->setLimit(5);

        return $cache->get() ?: [];
    }

}
