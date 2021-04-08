<?php

namespace App\Caches;

use App\Models\Article as ArticleModel;

class MaxArticleId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_article_id';
    }

    public function getContent($id = null)
    {
        $article = ArticleModel::findFirst(['order' => 'id DESC']);

        return $article->id ?? 0;
    }

}
