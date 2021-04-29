<?php

namespace App\Http\Home\Services;

use App\Http\Admin\Services\Article as ArticleService;
use App\Library\Utils\Word as WordUtil;
use App\Models\Article as ArticleModel;
use App\Traits\Client as ClientTrait;
use App\Validators\Article as ArticleValidator;

class Article extends ArticleService
{

    use ClientTrait;

    public function createArticle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $article = new ArticleModel();

        $data = $this->handlePostData($post);

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();
        $data['owner_id'] = $user->id;

        $article->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->incrUserArticleCount($user);

        $this->eventsManager->fire('Article:afterCreate', $this, $article);

        return $article;
    }

    public function updateArticle($id)
    {
        $post = $this->request->getPost();

        $article = $this->findOrFail($id);

        $data = $this->handlePostData($post);

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

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
                $data['cover'],
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

        $this->eventsManager->fire('Article:afterUpdate', $this, $article);

        return $article;
    }

    public function deleteArticle($id)
    {
        $article = $this->findOrFail($id);

        $user = $this->getLoginUser();

        $validator = new ArticleValidator();

        $validator->checkOwner($user->id, $article->owner_id);

        $article->deleted = 1;

        $article->update();

        $this->decrUserArticleCount($user);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterDelete', $this, $article);

        return $article;
    }

    protected function handlePostData($post)
    {
        $data = [];

        $validator = new ArticleValidator();

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);
        $data['word_count'] = WordUtil::getWordCount($data['content']);

        if (isset($post['category_id'])) {
            $category = $validator->checkCategory($post['category_id']);
            $data['category_id'] = $category->id;
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['source_type'])) {
            $data['source_type'] = $validator->checkSourceType($post['source_type']);
            if ($post['source_type'] != ArticleModel::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($post['source_url']);
            }
        }

        if (isset($post['allow_comment'])) {
            $data['allow_comment'] = $validator->checkAllowCommentStatus($post['allow_comment']);
        }

        if (isset($post['private'])) {
            $data['private'] = $validator->checkPrivateStatus($post['private']);
        }

        return $data;
    }

}
