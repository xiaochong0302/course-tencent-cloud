<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Article as ArticleService;
use App\Http\Home\Services\ArticleQuery as ArticleQueryService;
use App\Services\Logic\Article\ArticleFavorite as ArticleFavoriteService;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Article\ArticleLike as ArticleLikeService;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Article\HotAuthorList as HotAuthorListService;
use App\Services\Logic\Article\RelatedArticleList as RelatedArticleListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/article")
 */
class ArticleController extends Controller
{

    /**
     * @Get("/list", name="home.article.list")
     */
    public function listAction()
    {
        $service = new ArticleQueryService();

        $categories = $service->handleCategories();
        $sorts = $service->handleSorts();
        $params = $service->getParams();

        $this->seo->prependTitle('专栏');

        $this->view->setVar('categories', $categories);
        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('params', $params);
    }

    /**
     * @Get("/pager", name="home.article.pager")
     */
    public function pagerAction()
    {
        $service = new ArticleListService();

        $pager = $service->handle();

        $pager->target = 'article-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/hot/authors", name="home.article.hot_authors")
     */
    public function hotAuthorsAction()
    {
        $service = new HotAuthorListService();

        $authors = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('article/hot_authors');
        $this->view->setVar('authors', $authors);
    }

    /**
     * @Get("/add", name="home.article.add")
     */
    public function addAction()
    {
        $service = new ArticleService();

        $sourceTypes = $service->getSourceTypes();
        $categories = $service->getCategories();
        $article = $service->getArticleModel();
        $xmTags = $service->getXmTags(0);

        $this->seo->prependTitle('写文章');

        $this->view->pick('article/edit');
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('categories', $categories);
        $this->view->setVar('article', $article);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.article.edit")
     */
    public function editAction($id)
    {
        $service = new ArticleService();

        $sourceTypes = $service->getSourceTypes();
        $categories = $service->getCategories();
        $article = $service->getArticle($id);
        $xmTags = $service->getXmTags($id);

        $this->seo->prependTitle('编辑文章');

        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('categories', $categories);
        $this->view->setVar('article', $article);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.article.show")
     */
    public function showAction($id)
    {
        $service = new ArticleInfoService();

        $article = $service->handle($id);

        $owned = $this->authUser->id == $article['owner']['id'];

        if ($article['private'] == 1 && !$owned) {
            $this->response->redirect(['for' => 'home.error.403']);
        }

        $this->seo->prependTitle($article['title']);

        $this->view->setVar('article', $article);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.article.related")
     */
    public function relatedAction($id)
    {
        $service = new RelatedArticleListService();

        $articles = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('articles', $articles);
    }

    /**
     * @Post("/create", name="home.article.create")
     */
    public function createAction()
    {
        $service = new ArticleService();

        $service->createArticle();

        $location = $this->url->get(['for' => 'home.uc.articles']);

        $content = [
            'location' => $location,
            'msg' => '创建文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.article.update")
     */
    public function updateAction($id)
    {
        $service = new ArticleService();

        $service->updateArticle($id);

        $location = $this->url->get(['for' => 'home.uc.articles']);

        $content = [
            'location' => $location,
            'msg' => '更新文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.article.delete")
     */
    public function deleteAction($id)
    {
        $service = new ArticleService();

        $service->deleteArticle($id);

        $location = $this->url->get(['for' => 'home.uc.articles']);

        $content = [
            'location' => $location,
            'msg' => '删除文章成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.article.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new ArticleFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.article.like")
     */
    public function likeAction($id)
    {
        $service = new ArticleLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
