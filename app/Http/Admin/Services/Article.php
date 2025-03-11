<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Builders\ReportList as ReportListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\Reason as ReasonModel;
use App\Models\Report as ReportModel;
use App\Models\User as UserModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\Report as ReportRepo;
use App\Repos\User as UserRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Article\ArticleDataTrait;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Article\XmTagList as XmTagListService;
use App\Services\Logic\Notice\Internal\ArticleApproved as ArticleApprovedNotice;
use App\Services\Logic\Notice\Internal\ArticleRejected as ArticleRejectedNotice;
use App\Services\Logic\Point\History\ArticlePost as ArticlePostPointHistory;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;
use App\Validators\Article as ArticleValidator;

class Article extends Service
{

    use ArticleDataTrait;
    use AccountSearchTrait;

    public function getXmTags($id)
    {
        $service = new XmTagListService();

        return $service->handle($id);
    }

    public function getCategoryOptions()
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions(CategoryModel::TYPE_ARTICLE);
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

        $params = $this->handleAccountSearchParams($params);

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

    public function getReports($id)
    {
        $reportRepo = new ReportRepo();

        $where = [
            'item_id' => $id,
            'item_type' => ReportModel::ITEM_ARTICLE,
            'reviewed' => 0,
        ];

        $pager = $reportRepo->paginate($where);

        $pager = $this->handleReports($pager);

        return $pager->items;
    }

    public function createArticle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ArticleValidator();

        $title = $validator->checkTitle($post['title']);

        $article = new ArticleModel();

        $article->published = ArticleModel::PUBLISH_APPROVED;
        $article->client_type = $this->getClientType();
        $article->client_ip = $this->getClientIp();
        $article->owner_id = $user->id;
        $article->title = $title;

        $article->create();

        $this->saveDynamicAttrs($article);
        $this->rebuildArticleIndex($article);
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

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['source_type'])) {
            $data['source_type'] = $validator->checkSourceType($post['source_type']);
            if ($post['source_type'] != ArticleModel::SOURCE_ORIGIN) {
                $data['source_url'] = $validator->checkSourceUrl($post['source_url']);
            }
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['category_id'])) {
            $data['category_id'] = $validator->checkCategoryId($post['category_id']);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $article->update($data);

        $owner = $this->findUser($article->owner_id);

        $this->saveDynamicAttrs($article);
        $this->rebuildArticleIndex($article);
        $this->recountUserArticles($owner);

        $this->eventsManager->fire('Article:afterUpdate', $this, $article);

        return $article;
    }

    public function deleteArticle($id)
    {
        $article = $this->findOrFail($id);

        $article->deleted = 1;

        $article->update();

        $owner = $this->findUser($article->owner_id);

        $this->saveDynamicAttrs($article);
        $this->rebuildArticleIndex($article);
        $this->recountUserArticles($owner);

        $this->eventsManager->fire('Article:afterDelete', $this, $article);

        return $article;
    }

    public function restoreArticle($id)
    {
        $article = $this->findOrFail($id);

        $article->deleted = 0;

        $article->update();

        $owner = $this->findUser($article->owner_id);

        $this->saveDynamicAttrs($article);
        $this->rebuildArticleIndex($article);
        $this->recountUserArticles($owner);

        $this->eventsManager->fire('Article:afterRestore', $this, $article);

        return $article;
    }

    public function moderate($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $article = $this->findOrFail($id);
        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $article->published = ArticleModel::PUBLISH_APPROVED;
            $article->update();

            $this->handleArticlePostPoint($article);
            $this->handleArticleApprovedNotice($article, $sender);

            $this->eventsManager->fire('Article:afterApprove', $this, $article);

        } elseif ($type == 'reject') {

            $article->published = ArticleModel::PUBLISH_REJECTED;
            $article->update();

            $this->handleArticleRejectedNotice($article, $sender, $reason);

            $this->eventsManager->fire('Article:afterReject', $this, $article);
        }

        $owner = $this->findUser($article->owner_id);

        $this->rebuildArticleIndex($article);
        $this->recountUserArticles($owner);

        return $article;
    }

    public function report($id)
    {
        $accepted = $this->request->getPost('accepted', 'int', 0);
        $deleted = $this->request->getPost('deleted', 'int', 0);

        $article = $this->findOrFail($id);

        $reportRepo = new ReportRepo();

        $reports = $reportRepo->findItemPendingReports($article->id, ReportModel::ITEM_ARTICLE);

        if ($reports->count() > 0) {
            foreach ($reports as $report) {
                $report->accepted = $accepted;
                $report->reviewed = 1;
                $report->update();
            }
        }

        $article->report_count = 0;

        if ($deleted == 1) {
            $article->deleted = 1;
        }

        $article->update();

        $owner = $this->findUser($article->owner_id);

        $this->rebuildArticleIndex($article);
        $this->recountUserArticles($owner);
    }

    public function batchModerate()
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $articleRepo = new ArticleRepo();

        $articles = $articleRepo->findByIds($ids);

        if ($articles->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($articles as $article) {

            if ($type == 'approve') {

                $article->published = ArticleModel::PUBLISH_APPROVED;
                $article->update();

                $this->handleArticlePostPoint($article);
                $this->handleArticleApprovedNotice($article, $sender);

            } elseif ($type == 'reject') {

                $article->published = ArticleModel::PUBLISH_REJECTED;
                $article->update();

                $this->handleArticleRejectedNotice($article, $sender);
            }

            $owner = $this->findUser($article->owner_id);

            $this->recountUserArticles($owner);
            $this->rebuildArticleIndex($article);
        }
    }

    public function batchDelete()
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $articleRepo = new ArticleRepo();

        $articles = $articleRepo->findByIds($ids);

        if ($articles->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($articles as $article) {

            $article->deleted = 1;
            $article->update();

            $this->handleArticleDeletedNotice($article, $sender);

            $owner = $this->findUser($article->owner_id);

            $this->recountUserArticles($owner);
            $this->rebuildArticleIndex($article);
        }
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

    protected function rebuildArticleIndex(ArticleModel $article)
    {
        $sync = new ArticleIndexSync();

        $sync->addItem($article->id);
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

    protected function handleArticleApprovedNotice(ArticleModel $article, UserModel $sender)
    {
        $notice = new ArticleApprovedNotice();

        $notice->handle($article, $sender);
    }

    protected function handleArticleRejectedNotice(ArticleModel $article, UserModel $sender, $reason = '')
    {
        $notice = new ArticleRejectedNotice();

        $notice->handle($article, $sender, $reason);
    }

    protected function handleArticleDeletedNotice(ArticleModel $article, UserModel $sender, $reason = '')
    {

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

    protected function handleReports($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReportListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
