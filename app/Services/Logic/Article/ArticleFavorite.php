<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\ArticleFavorite as ArticleFavoriteModel;
use App\Models\User as UserModel;
use App\Repos\ArticleFavorite as ArticleFavoriteRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Notice\Internal\ArticleFavorited as ArticleFavoritedNotice;
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

        $isFirstTime = true;

        if (!$favorite) {

            $favorite = new ArticleFavoriteModel();

            $favorite->article_id = $article->id;
            $favorite->user_id = $user->id;

            $favorite->create();

        } else {

            $isFirstTime = false;

            $favorite->deleted = $favorite->deleted == 1 ? 0 : 1;

            $favorite->update();
        }

        if ($favorite->deleted == 0) {

            $action = 'do';

            $this->incrArticleFavoriteCount($article);
            $this->incrUserFavoriteCount($user);

            $this->eventsManager->fire('Article:afterFavorite', $this, $article);

        } else {

            $action = 'undo';

            $this->decrArticleFavoriteCount($article);
            $this->decrUserFavoriteCount($user);

            $this->eventsManager->fire('Article:afterUndoFavorite', $this, $article);
        }

        $isOwner = $user->id == $article->owner_id;

        /**
         * 仅首次收藏发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleFavoriteNotice($article, $user);
        }

        return [
            'action' => $action,
            'count' => $article->favorite_count,
        ];
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

    protected function handleFavoriteNotice(ArticleModel $article, UserModel $sender)
    {
        $notice = new ArticleFavoritedNotice();

        $notice->handle($article, $sender);
    }

}
