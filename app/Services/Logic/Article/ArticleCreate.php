<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\History\ArticlePost as ArticlePostPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ArticleCreate extends LogicService
{

    use ArticleDataTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyArticleLimit($user);

        $article = new ArticleModel();

        $data = $this->handlePostData($post);

        $data['published'] = $this->getPublishStatus($user);
        $data['owner_id'] = $user->id;

        $article->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($article);
        $this->incrUserDailyArticleCount($user);
        $this->recountUserArticles($user);

        if ($article->published == ArticleModel::PUBLISH_APPROVED) {
            $this->handleArticlePostPoint($article);
        }

        $this->eventsManager->fire('Article:afterCreate', $this, $article);

        return $article;
    }

    protected function incrUserDailyArticleCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrArticleCount', $this, $user);
    }

    protected function recountUserArticles(UserModel $user)
    {
        $userRepo = new UserRepo();

        $articleCount = $userRepo->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->update();
    }

    protected function handleArticlePostPoint(ArticleModel $article)
    {
        if ($article->published != ArticleModel::PUBLISH_APPROVED) return;

        $service = new ArticlePostPointHistory();

        $service->handle($article);
    }

}
