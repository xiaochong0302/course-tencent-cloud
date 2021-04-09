<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\ArticleLike as ArticleLikeModel;
use App\Models\User as UserModel;
use App\Repos\ArticleLike as ArticleLikeRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service;
use App\Validators\UserLimit as UserLimitValidator;

class ArticleLike extends Service
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyArticleLikeLimit($user);

        $likeRepo = new ArticleLikeRepo();

        $articleLike = $likeRepo->findArticleLike($article->id, $user->id);

        if (!$articleLike) {

            $articleLike = new ArticleLikeModel();

            $articleLike->article_id = $article->id;
            $articleLike->user_id = $user->id;

            $articleLike->create();

            $this->incrArticleLikeCount($article);

        } else {

            $articleLike->delete();

            $this->decrArticleLikeCount($article);
        }

        $this->incrUserDailyArticleLikeCount($user);

        return $article->like_count;
    }

    protected function incrArticleLikeCount(ArticleModel $article)
    {
        $article->like_count += 1;

        $article->update();
    }

    protected function decrArticleLikeCount(ArticleModel $article)
    {
        if ($article->like_count > 0) {
            $article->like_count -= 1;
            $article->update();
        }
    }

    protected function incrUserDailyArticleLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrArticleLikeCount', $this, $user);
    }

}
