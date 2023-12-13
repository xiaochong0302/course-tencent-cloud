<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Article as ArticleService;
use App\Models\Category as CategoryModel;

/**
 * @RoutePrefix("/admin/article")
 */
class ArticleController extends Controller
{

    /**
     * @Get("/category", name="admin.article.category")
     */
    public function categoryAction()
    {
        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['type' => CategoryModel::TYPE_ARTICLE]
        );

        return $this->response->redirect($location);
    }

    /**
     * @Get("/search", name="admin.article.search")
     */
    public function searchAction()
    {
        $articleService = new ArticleService();

        $publishTypes = $articleService->getPublishTypes();
        $sourceTypes = $articleService->getSourceTypes();
        $categoryOptions = $articleService->getCategoryOptions();
        $xmTags = $articleService->getXmTags(0);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/list", name="admin.article.list")
     */
    public function listAction()
    {
        $articleService = new ArticleService();

        $pager = $articleService->getArticles();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.article.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.article.edit")
     */
    public function editAction($id)
    {
        $articleService = new ArticleService();

        $publishTypes = $articleService->getPublishTypes();
        $sourceTypes = $articleService->getSourceTypes();
        $categoryOptions = $articleService->getCategoryOptions();
        $article = $articleService->getArticle($id);
        $xmTags = $articleService->getXmTags($id);

        $this->view->setVar('article', $article);
        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.article.show")
     */
    public function showAction($id)
    {
        $articleService = new ArticleService();

        $article = $articleService->getArticle($id);

        $this->view->setVar('article', $article);
    }

    /**
     * @Post("/create", name="admin.article.create")
     */
    public function createAction()
    {
        $articleService = new ArticleService();

        $article = $articleService->createArticle();

        $location = $this->url->get([
            'for' => 'admin.article.edit',
            'id' => $article->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.article.update")
     */
    public function updateAction($id)
    {
        $articleService = new ArticleService();

        $articleService->updateArticle($id);

        $content = ['msg' => '更新文章成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.article.delete")
     */
    public function deleteAction($id)
    {
        $articleService = new ArticleService();

        $articleService->deleteArticle($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.article.restore")
     */
    public function restoreAction($id)
    {
        $articleService = new ArticleService();

        $articleService->restoreArticle($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Route("/{id:[0-9]+}/moderate", name="admin.article.moderate")
     */
    public function moderateAction($id)
    {
        $articleService = new ArticleService();

        if ($this->request->isPost()) {

            $articleService->moderate($id);

            $location = $this->url->get(['for' => 'admin.mod.articles']);

            $content = [
                'location' => $location,
                'msg' => '审核文章成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $articleService->getReasons();
        $article = $articleService->getArticleInfo($id);

        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('article', $article);
    }

    /**
     * @Route("/{id:[0-9]+}/report", name="admin.article.report")
     */
    public function reportAction($id)
    {
        $articleService = new ArticleService();

        if ($this->request->isPost()) {

            $articleService->report($id);

            $location = $this->url->get(['for' => 'admin.report.articles']);

            $content = [
                'location' => $location,
                'msg' => '处理举报成功',
            ];

            return $this->jsonSuccess($content);
        }

        $article = $articleService->getArticleInfo($id);
        $reports = $articleService->getReports($id);

        $this->view->setVar('reports', $reports);
        $this->view->setVar('article', $article);
    }

    /**
     * @Post("/moderate/batch", name="admin.article.batch_moderate")
     */
    public function batchModerateAction()
    {
        $articleService = new ArticleService();

        $articleService->batchModerate();

        $location = $this->url->get(['for' => 'admin.mod.articles']);

        $content = [
            'location' => $location,
            'msg' => '批量审核成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/delete/batch", name="admin.article.batch_delete")
     */
    public function batchDeleteAction()
    {
        $articleService = new ArticleService();

        $articleService->batchDelete();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '批量删除成功',
        ];

        return $this->jsonSuccess($content);
    }

}
