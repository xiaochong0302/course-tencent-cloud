<?php

namespace App\Services\Logic;

use App\Validators\Article as ArticleValidator;

trait ArticleTrait
{

    public function checkArticle($id)
    {
        $validator = new ArticleValidator();

        return $validator->checkArticle($id);
    }

    public function checkArticleCache($id)
    {
        $validator = new ArticleValidator();

        return $validator->checkArticleCache($id);
    }

}
