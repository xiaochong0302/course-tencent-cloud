<?php

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;

class ArticleUpdate extends LogicService
{

    use ArticleTrait;
    use ArticleDataTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $article = $this->checkArticle($id);

        $data = $this->handlePostData($post);

        if ($article->published == ArticleModel::PUBLISH_REJECTED) {
            $data['published'] = ArticleModel::PUBLISH_PENDING;
        }

        /**
         * 当通过审核后，禁止修改部分文章属性
         */
        if ($article->published == ArticleModel::PUBLISH_APPROVED) {
            unset(
                $data['title'],
                $data['content'],
                $data['source_type'],
                $data['source_url'],
                $data['category_id'],
                $post['xm_tag_ids'],
            );
        }

        $article->update($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($article);

        $this->eventsManager->fire('Article:afterUpdate', $this, $article);

        return $article;
    }

}
