<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class ArticleCreate extends LogicService
{

    use ArticleDataTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $article = new ArticleModel();

        $data = $this->handlePostData($post);

        $data['published'] = $this->getPublishStatus($user);

        $data['owner_id'] = $user->id;

        $article->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->recountUserArticles($user);

        $this->eventsManager->fire('Article:afterCreate', $this, $article);

        return $article;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->article_count > 100 ? ArticleModel::PUBLISH_APPROVED : ArticleModel::PUBLISH_PENDING;
    }

    protected function recountUserArticles(UserModel $user)
    {
        $userRepo = new UserRepo();

        $articleCount = $userRepo->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->update();
    }

}
