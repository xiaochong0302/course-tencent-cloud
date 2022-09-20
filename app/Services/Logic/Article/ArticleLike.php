<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\ArticleLike as ArticleLikeModel;
use App\Models\User as UserModel;
use App\Repos\ArticleLike as ArticleLikeRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Notice\Internal\ArticleLiked as ArticleLikedNotice;
use App\Services\Logic\Point\History\ArticleLiked as ArticleLikedPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ArticleLike extends LogicService
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

        $isFirstTime = true;

        if (!$articleLike) {

            $articleLike = new ArticleLikeModel();

            $articleLike->article_id = $article->id;
            $articleLike->user_id = $user->id;

            $articleLike->create();

        } else {

            $isFirstTime = false;

            $articleLike->deleted = $articleLike->deleted == 1 ? 0 : 1;

            $articleLike->update();
        }

        $this->incrUserDailyArticleLikeCount($user);

        if ($articleLike->deleted == 0) {

            $action = 'do';

            $this->incrArticleLikeCount($article);

            $this->eventsManager->fire('Article:afterLike', $this, $article);

        } else {

            $action = 'undo';

            $this->decrArticleLikeCount($article);

            $this->eventsManager->fire('Article:afterUndoLike', $this, $article);
        }

        $isOwner = $user->id == $article->owner_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleArticleLikedNotice($article, $user);
            $this->handleArticleLikedPoint($articleLike);
        }

        return [
            'action' => $action,
            'count' => $article->like_count,
        ];
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

    protected function handleArticleLikedNotice(ArticleModel $article, UserModel $sender)
    {
        $notice = new ArticleLikedNotice();

        $notice->handle($article, $sender);
    }

    protected function handleArticleLikedPoint(ArticleLikeModel $articleLike)
    {
        $service = new ArticleLikedPointHistory();

        $service->handle($articleLike);
    }

}
