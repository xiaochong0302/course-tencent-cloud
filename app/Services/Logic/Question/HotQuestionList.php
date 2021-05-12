<?php

namespace App\Services\Logic\Question;

use App\Caches\HotQuestionList as HotQuestionListCache;
use App\Services\Logic\Service as LogicService;

class HotQuestionList extends LogicService
{

    public function handle()
    {
        $cache = new HotQuestionListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
