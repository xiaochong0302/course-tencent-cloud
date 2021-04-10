<?php

namespace App\Services\Logic\Article;

use App\Caches\ArticleHotAuthorList as ArticleHotAuthorListCache;
use App\Services\Logic\Service as LogicService;

class HotAuthorList extends LogicService
{

    public function handle()
    {
        $cache = new ArticleHotAuthorListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
