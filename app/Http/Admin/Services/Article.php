<?php

namespace App\Http\Admin\Services;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Caches\Article as ArticleCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Library\Utils\Word as WordUtil;
use App\Models\Article as ArticleModel;
use App\Models\ArticleTag as ArticleTagModel;
use App\Models\Category as CategoryModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\ArticleTag as ArticleTagRepo;
use App\Repos\Category as CategoryRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;
use App\Validators\Article as ArticleValidator;

class Article extends Service
{

    public function getXmTags($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1], 'priority');

        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = $this->findOrFail($id);
            if (!empty($article->tags)) {
                $articleTagIds = kg_array_column($article->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $selected = in_array($tag->id, $articleTagIds);
            $list[] = [
                'name' => $tag->name,
                'value' => $tag->id,
                'selected' => $selected,
            ];
        }

        return $list;
    }

    public function getCategories()
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'type' => CategoryModel::TYPE_ARTICLE,
            'level' => 1,
            'published' => 1,
        ]);
    }

    public function getSourceTypes()
    {
        return ArticleModel::sourceTypes();
    }

    public function getArticles()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (!empty($params['xm_tag_ids'])) {
            $params['tag_id'] = explode(',', $params['xm_tag_ids']);
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new ArticleRepo();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    public function getArticle($id)
    {
        return $this->findOrFail($id);
    }

    public function createArticle()
    {
        $post = $this->request->getPost();

        $loginUser = $this->getLoginUser();

        $validator = new ArticleValidator();

        $category = $validator->checkCategory($post['category_id']);
        $title = $validator->checkTitle($post['title']);

        $article = new ArticleModel();

        $article->owner_id = $loginUser->id;
        $article->category_id = $category->id;
        $article->title = $title;

        $article->create();

        return $article;
    }

    public function updateArticle($id)
    {
        $post = $this->request->getPost();

        $article = $this->findOrFail($id);

        $validator = new ArticleValidator();

        $data = [];

        if (isset($post['category_id'])) {
            $category = $validator->checkCategory($post['category_id']);
            $data['category_id'] = $category->id;
        }

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
            $data['word_count'] = WordUtil::getWordCount($data['content']);
        }

        if (isset($post['source_type'])) {
            $data['source_type'] = $validator->checkSourceType($post['source_type']);
            if ($post['source_type'] != ArticleModel::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($post['source_url']);
            }
        }

        if (isset($post['allow_comment'])) {
            $data['allow_comment'] = $post['allow_comment'];
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $article->update($data);

        return $article;
    }

    public function deleteArticle($id)
    {
        $article = $this->findOrFail($id);
        $article->deleted = 1;
        $article->update();

        return $article;
    }

    public function restoreArticle($id)
    {
        $article = $this->findOrFail($id);
        $article->deleted = 0;
        $article->update();

        return $article;
    }

    protected function findOrFail($id)
    {
        $validator = new ArticleValidator();

        return $validator->checkArticle($id);
    }

    protected function rebuildArticleCache(ArticleModel $article)
    {
        $cache = new ArticleCache();

        $cache->rebuild($article->id);
    }

    protected function rebuildArticleIndex(ArticleModel $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
    }

    protected function saveTags(ArticleModel $article, $tagIds)
    {
        $originTagIds = [];

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

    protected function handleArticles($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ArticleListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleArticles($items);
            $pipeB = $builder->handleCategories($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
