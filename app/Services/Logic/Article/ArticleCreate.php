<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Models\User as UserModel;
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

        $data['owner_id'] = $user->id;

        $article->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->incrUserArticleCount($user);

        $this->eventsManager->fire('Article:afterCreate', $this, $article);

        return $article;
    }

    protected function incrUserArticleCount(UserModel $user)
    {
        $user->article_count += 1;

        $user->update();
    }

}
