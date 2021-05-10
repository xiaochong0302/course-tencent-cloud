<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\User as UserModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;
use App\Validators\Article as ArticleValidator;

class ArticleDelete extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        $user = $this->getLoginUser();

        $validator = new ArticleValidator();

        $validator->checkOwner($user->id, $article->owner_id);

        $article->deleted = 1;

        $article->update();

        $this->decrUserArticleCount($user);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterDelete', $this, $article);
    }

    protected function decrUserArticleCount(UserModel $user)
    {
        if ($user->article_count > 0) {
            $user->article_count -= 1;
            $user->update();
        }
    }

    protected function rebuildArticleIndex(ArticleModel $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
    }

}
