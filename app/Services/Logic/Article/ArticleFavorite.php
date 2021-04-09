<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\ArticleFavorite as ArticleFavoriteModel;
use App\Models\User as UserModel;
use App\Repos\ArticleFavorite as ArticleFavoriteRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ArticleFavorite extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new ArticleFavoriteRepo();

        $favorite = $favoriteRepo->findArticleFavorite($article->id, $user->id);

        if (!$favorite) {

            $favorite = new ArticleFavoriteModel();

            $favorite->article_id = $article->id;
            $favorite->user_id = $user->id;

            $favorite->create();

            $this->incrArticleFavoriteCount($article);
            $this->incrUserFavoriteCount($user);

        } else {

            $favorite->delete();

            $this->decrArticleFavoriteCount($article);
            $this->decrUserFavoriteCount($user);
        }

        return $article->favorite_count;
    }

    protected function incrArticleFavoriteCount(ArticleModel $article)
    {
        $article->favorite_count += 1;

        $article->update();
    }

    protected function decrArticleFavoriteCount(ArticleModel $article)
    {
        if ($article->favorite_count > 0) {
            $article->favorite_count -= 1;
            $article->update();
        }
    }

    protected function incrUserFavoriteCount(UserModel $user)
    {
        $user->favorite_count += 1;

        $user->update();
    }

    protected function decrUserFavoriteCount(UserModel $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->update();
        }
    }

}
