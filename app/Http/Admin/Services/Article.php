<?php

namespace App\Http\Admin\Services;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Caches\Article as ArticleCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Library\Utils\Word as WordUtil;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Reason as ReasonModel;
use App\Models\User as UserModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\Category as CategoryRepo;
use App\Repos\Tag as TagRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Article\ArticleDataTrait;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Notice\System\ArticleApproved as ArticleApprovedNotice;
use App\Services\Logic\Notice\System\ArticleRejected as ArticleRejectedNotice;
use App\Services\Logic\Point\History\ArticlePost as ArticlePostPointHistory;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;
use App\Validators\Article as ArticleValidator;

class Article extends Service
{

    use ArticleDataTrait;

    public function getXmTags($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1]);

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

    public function getPublishTypes()
    {
        return ArticleModel::publishTypes();
    }

    public function getSourceTypes()
    {
        return ArticleModel::sourceTypes();
    }

    public function getReasons()
    {
        return ReasonModel::articleRejectOptions();
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

    public function getArticleInfo($id)
    {
        $service = new ArticleInfoService();

        return $service->handle($id);
    }

    public function createArticle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ArticleValidator();

        $title = $validator->checkTitle($post['title']);

        $article = new ArticleModel();

        $article->published = ArticleModel::PUBLISH_APPROVED;
        $article->owner_id = $user->id;
        $article->title = $title;

        $article->create();

        $this->recountUserArticles($user);

        $this->eventsManager->fire('Article:afterCreate', $this, $article);

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

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        if (isset($post['private'])) {
            $data['private'] = $validator->checkPrivateStatus($post['private']);
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['published'])) {

            $data['published'] = $validator->checkPublishStatus($post['published']);

            $owner = $this->findUser($article->owner_id);

            $this->recountUserArticles($owner);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $article->update($data);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterUpdate', $this, $article);

        return $article;
    }

    public function deleteArticle($id)
    {
        $article = $this->findOrFail($id);

        $article->deleted = 1;

        $article->update();

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($article->owner_id);

        $this->recountUserArticles($owner);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterDelete', $this, $article);

        return $article;
    }

    public function restoreArticle($id)
    {
        $article = $this->findOrFail($id);

        $article->deleted = 0;

        $article->update();

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($article->owner_id);

        $this->recountUserArticles($owner);

        $this->rebuildArticleIndex($article);

        $this->eventsManager->fire('Article:afterRestore', $this, $article);

        return $article;
    }

    public function reviewArticle($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $article = $this->findOrFail($id);

        $validator = new ArticleValidator();

        if ($type == 'approve') {

            $article->published = ArticleModel::PUBLISH_APPROVED;

        } elseif ($type == 'reject') {

            $validator->checkRejectReason($reason);

            $article->published = ArticleModel::PUBLISH_REJECTED;
        }

        $article->update();

        $owner = $this->findUser($article->owner_id);

        $this->recountUserArticles($owner);

        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $this->rebuildArticleIndex($article);

            $this->handlePostPoint($article);

            $notice = new ArticleApprovedNotice();

            $notice->handle($article, $sender);

            $this->eventsManager->fire('Article:afterApprove', $this, $article);

        } elseif ($type == 'reject') {

            $options = ReasonModel::articleRejectOptions();

            if (array_key_exists($reason, $options)) {
                $reason = $options[$reason];
            }

            $notice = new ArticleRejectedNotice();

            $notice->handle($article, $sender, $reason);

            $this->eventsManager->fire('Article:afterReject', $this, $article);
        }

        return $article;
    }

    protected function findOrFail($id)
    {
        $validator = new ArticleValidator();

        return $validator->checkArticle($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($id);
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

    protected function recountUserArticles(UserModel $user)
    {
        $userRepo = new UserRepo();

        $articleCount = $userRepo->countArticles($user->id);

        $user->article_count = $articleCount;

        $user->update();
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

    protected function handlePostPoint(ArticleModel $article)
    {
        if ($article->published != ArticleModel::PUBLISH_APPROVED) return;

        $service = new ArticlePostPointHistory();

        $service->handle($article);
    }

}
