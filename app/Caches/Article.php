<?php

namespace App\Caches;

use App\Repos\Article as ArticleRepo;

class Article extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "article:{$id}";
    }

    public function getContent($id = null)
    {
        $articleRepo = new ArticleRepo();

        $article = $articleRepo->findById($id);

        return $article ?: null;
    }

}
