<?php

namespace App\Services\Logic\Article;

use App\Caches\ArticleRelatedList as ArticleRelatedListCache;
use App\Services\Logic\Service as LogicService;

class RelatedList extends LogicService
{

    public function handle($id)
    {
        $cache = new ArticleRelatedListCache();

        $result = $cache->get($id);

        return $result ?: [];
    }

}
