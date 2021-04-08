<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ArticleQuery as ArticleQueryService;
use App\Services\Logic\Article\ArticleFavorite as ArticleFavoriteService;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Article\ArticleLike as ArticleLikeService;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Article\CommentList as ArticleCommentListService;
use App\Services\Logic\Article\HotAuthorList as HotAuthorListService;
use App\Services\Logic\Article\RelatedList as ArticleRelatedListService;
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

        $this->seo->prependTitle('文章');

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
     * @Get("/{id:[0-9]+}", name="home.article.show")
     */
    public function showAction($id)
    {
        $service = new ArticleInfoService();

        $article = $service->handle($id);

        $this->seo->prependTitle($article['title']);

        $this->view->setVar('article', $article);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.article.related")
     */
    public function relatedAction($id)
    {
        $service = new ArticleRelatedListService();

        $articles = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('articles', $articles);
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="home.article.comments")
     */
    public function commentsAction($id)
    {
        $service = new ArticleCommentListService();

        $comments = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('comments', $comments);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.article.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new ArticleFavoriteService();

        $favoriteCount = $service->handle($id);

        return $this->jsonSuccess(['favorite_count' => $favoriteCount]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.article.like")
     */
    public function likeAction($id)
    {
        $service = new ArticleLikeService();

        $likeCount = $service->handle($id);

        return $this->jsonSuccess(['like_count' => $likeCount]);
    }

}
