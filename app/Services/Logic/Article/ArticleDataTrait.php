<?php

namespace App\Services\Logic\Article;

use App\Library\Utils\Word as WordUtil;
use App\Models\Article as ArticleModel;
use App\Models\ArticleTag as ArticleTagModel;
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

        $validator = new ArticleValidator();

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);
        $data['word_count'] = WordUtil::getWordCount($data['content']);

        if (isset($post['category_id'])) {
            $category = $validator->checkCategory($post['category_id']);
            $data['category_id'] = $category->id;
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
            }
        }

        $deletedTagIds = array_diff($originTagIds, $newTagIds);

        if ($deletedTagIds) {
            $articleTagRepo = new ArticleTagRepo();
            foreach ($deletedTagIds as $tagId) {
                $articleTag = $articleTagRepo->findArticleTag($article->id, $tagId);
                if ($articleTag) {
                    $articleTag->delete();
                }
            }
        }

        $articleTags = [];

        if ($newTagIds) {
            $tagRepo = new TagRepo();
            $tags = $tagRepo->findByIds($newTagIds);
            if ($tags->count() > 0) {
                $articleTags = [];
                foreach ($tags as $tag) {
                    $articleTags[] = ['id' => $tag->id, 'name' => $tag->name];
                }
            }
        }

        $article->tags = $articleTags;

        $article->update();
    }

}
