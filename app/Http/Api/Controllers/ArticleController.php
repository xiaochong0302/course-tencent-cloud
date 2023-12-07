<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Models\Article as ArticleModel;
use App\Services\Logic\Article\ArticleClose as ArticleCloseService;
use App\Services\Logic\Article\ArticleDelete as ArticleDeleteService;
use App\Services\Logic\Article\ArticleFavorite as ArticleFavoriteService;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Article\ArticleLike as ArticleLikeService;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Article\CategoryList as CategoryListService;
use App\Services\Logic\Article\CommentList as CommentListService;

/**
 * @RoutePrefix("/api/article")
 */
class ArticleController extends Controller
{

    /**
     * @Get("/categories", name="api.article.categories")
     */
    public function categoriesAction()
    {
        $service = new CategoryListService();

        $categories = $service->handle();

        return $this->jsonSuccess(['categories' => $categories]);
    }

    /**
     * @Get("/list", name="api.article.list")
     */
    public function listAction()
    {
        $service = new ArticleListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.article.info")
     */
    public function infoAction($id)
    {
        $service = new ArticleInfoService();

        $article = $service->handle($id);

        if ($article['deleted'] == 1) {
            $this->notFound();
        }

        $approved = $article['published'] == ArticleModel::PUBLISH_APPROVED;
        $owned = $article['me']['owned'] == 1;

        if (!$approved && !$owned) {
            $this->notFound();
        }

        return $this->jsonSuccess(['article' => $article]);
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="api.article.comments")
     */
    public function commentsAction($id)
    {
        $service = new CommentListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="api.article.delete")
     */
    public function deleteAction($id)
    {
        $service = new ArticleDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/close", name="home.article.close")
     */
    public function closeAction($id)
    {
        $service = new ArticleCloseService();

        $article = $service->handle($id);

        $msg = $article->closed == 1 ? '关闭评论成功' : '开启评论成功';

        return $this->jsonSuccess(['msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="api.article.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new ArticleFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.article.like")
     */
    public function likeAction($id)
    {
        $service = new ArticleLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
