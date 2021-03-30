<?php

namespace App\Services\Logic\Point;

use App\Caches\PointHotGiftList;
use App\Services\Logic\Service as LogicService;

class HotGiftList extends LogicService
{

    public function handle()
    {
        $cache = new PointHotGiftList();

        $cache->setLimit(5);

        return $cache->get() ?: [];
    }

}
