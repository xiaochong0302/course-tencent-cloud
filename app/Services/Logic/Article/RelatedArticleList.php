<?php

namespace App\Services\Logic\Article;

use App\Caches\TaggedArticleList as TaggedArticleListCache;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;

class RelatedArticleList extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        if (empty($article->tags)) return [];

        $tagIds = kg_array_column($article->tags, 'id');

        $randKey = array_rand($tagIds);

        $tagId = $tagIds[$randKey];

        $cache = new TaggedArticleListCache();

        $result = $cache->get($tagId);

        return $result ?: [];
    }

}
