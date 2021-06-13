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

        $this->recountUserArticles($user);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterDelete', $this, $article);
    }

    protected function recountUserArticles(UserModel $user)
    {
        $userRepo = new UserRepo();

        $articleCount = $userRepo->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->update();
    }

    protected function rebuildArticleIndex(ArticleModel $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
    }

}
