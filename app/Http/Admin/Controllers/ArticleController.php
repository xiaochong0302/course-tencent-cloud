<?php

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

        $this->response->redirect($location);
    }

    /**
     * @Get("/search", name="admin.article.search")
     */
    public function searchAction()
    {
        $articleService = new ArticleService();

        $publishTypes = $articleService->getPublishTypes();
        $sourceTypes = $articleService->getSourceTypes();
        $categories = $articleService->getCategories();
        $xmTags = $articleService->getXmTags(0);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('categories', $categories);
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
        $articleService = new ArticleService();

        $categories = $articleService->getCategories();

        $this->view->setVar('categories', $categories);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.article.edit")
     */
    public function editAction($id)
    {
        $articleService = new ArticleService();

        $publishTypes = $articleService->getPublishTypes();
        $sourceTypes = $articleService->getSourceTypes();
        $categories = $articleService->getCategories();
        $article = $articleService->getArticle($id);
        $xmTags = $articleService->getXmTags($id);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('categories', $categories);
        $this->view->setVar('article', $article);
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
     * @Route("/{id:[0-9]+}/review", name="admin.article.review")
     */
    public function reviewAction($id)
    {
        $articleService = new ArticleService();

        if ($this->request->isPost()) {

            $articleService->reviewArticle($id);

            $location = $this->url->get(['for' => 'admin.mod.articles']);

            $content = [
                'location' => $location,
                'msg' => '审核文章成功',
            ];

            return $this->jsonSuccess($content);
        }

        $rejectOptions = $articleService->getRejectOptions();
        $article = $articleService->getArticle($id);

        $this->view->setVar('reject_options', $rejectOptions);
        $this->view->setVar('article', $article);
    }

}
