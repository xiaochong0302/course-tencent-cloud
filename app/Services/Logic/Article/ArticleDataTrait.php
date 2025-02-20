<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Library\Utils\Word as WordUtil;
use App\Models\Article as ArticleModel;
use App\Models\ArticleTag as ArticleTagModel;
use App\Models\User as UserModel;
use App\Repos\ArticleTag as ArticleTagRepo;
use App\Repos\Tag as TagRepo;
use App\Traits\Client as ClientTrait;
use App\Validators\Article as ArticleValidator;

trait ArticleDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new ArticleValidator();

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['category_id'])) {
            $data['category_id'] = $validator->checkCategoryId($post['category_id']);
        }

        if (isset($post['source_type'])) {
            $data['source_type'] = $validator->checkSourceType($post['source_type']);
            if ($post['source_type'] != ArticleModel::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($post['source_url']);
            }
        }

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        return $data;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->article_count > 100 ? ArticleModel::PUBLISH_APPROVED : ArticleModel::PUBLISH_PENDING;
    }

    protected function saveDynamicAttrs(ArticleModel $article)
    {
        if (empty($article->cover)) {
            $article->cover = kg_parse_first_content_image($article->content);
        }

        if (empty($article->summary)) {
            $article->summary = kg_parse_summary($article->content);
        }

        $article->word_count = WordUtil::getWordCount($article->content);

        $article->update();

        /**
         * 重新执行afterFetch
         */
        $article->afterFetch();
    }

    protected function saveTags(ArticleModel $article, $tagIds)
    {
        $originTagIds = [];

        /**
         * 修改数据后，afterFetch设置的属性会失效，重新执行
         */
        $article->afterFetch();

        if ($article->tags) {
            $originTagIds = kg_array_column($article->tags, 'id');
        }

        $newTagIds = $tagIds ? explode(',', $tagIds) : [];
        $addedTagIds = array_diff($newTagIds, $originTagIds);

        if ($addedTagIds) {
            foreach ($addedTagIds as $tagId) {
                $articleTag = new ArticleTagModel();
                $articleTag->article_id = $article->id;
                $articleTag->tag_id = $tagId;
                $articleTag->create();
                $this->recountTagArticles($tagId);
            }
        }

        $deletedTagIds = array_diff($originTagIds, $newTagIds);

        if ($deletedTagIds) {
            $articleTagRepo = new ArticleTagRepo();
            foreach ($deletedTagIds as $tagId) {
                $articleTag = $articleTagRepo->findArticleTag($article->id, $tagId);
                if ($articleTag) {
                    $articleTag->delete();
                    $this->recountTagArticles($tagId);
                }
            }
        }

        $articleTags = [];

        if ($newTagIds) {
            $tagRepo = new TagRepo();
            $tags = $tagRepo->findByIds($newTagIds);
            if ($tags->count() > 0) {
                foreach ($tags as $tag) {
                    $articleTags[] = ['id' => $tag->id, 'name' => $tag->name];
                    $this->recountTagArticles($tag->id);
                }
            }
        }

        $article->tags = $articleTags;

        $article->update();
    }

    protected function recountTagArticles($tagId)
    {
        $tagRepo = new TagRepo();

        $tag = $tagRepo->findById($tagId);

        if (!$tag) return;

        $articleCount = $tagRepo->countArticles($tagId);

        $tag->article_count = $articleCount;

        $tag->update();
    }

}
