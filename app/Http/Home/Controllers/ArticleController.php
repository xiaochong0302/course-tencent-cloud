<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Article as ArticleService;
use App\Http\Home\Services\ArticleQuery as ArticleQueryService;
use App\Models\Article as ArticleModel;
use App\Services\Logic\Article\ArticleClose as ArticleCloseService;
use App\Services\Logic\Article\ArticleCreate as ArticleCreateService;
use App\Services\Logic\Article\ArticleDelete as ArticleDeleteService;
use App\Services\Logic\Article\ArticleFavorite as ArticleFavoriteService;
use App\Services\Logic\Article\ArticleInfo as ArticleInfoService;
use App\Services\Logic\Article\ArticleLike as ArticleLikeService;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Article\ArticleUpdate as ArticleUpdateService;
use App\Services\Logic\Article\RelatedArticleList as RelatedArticleListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
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
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getArticleListUrl();
            return $this->response->redirect($location);
        }

        $service = new ArticleQueryService();

        $topCategories = $service->handleTopCategories();
        $subCategories = $service->handleSubCategories();
        $sorts = $service->handleSorts();
        $params = $service->getParams();

        $this->seo->prependTitle('专栏');

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
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
     * @Get("/add", name="home.article.add")
     */
    public function addAction()
    {
        $service = new ArticleService();

        $article = $service->getArticleModel();
        $categoryOptions = $service->getCategoryOptions();
        $sourceTypes = $service->getSourceTypes();
        $xmTags = $service->getXmTags(0);

        $this->seo->prependTitle('撰写文章');

        $this->view->pick('article/edit');
        $this->view->setVar('article', $article);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.article.edit")
     */
    public function editAction($id)
    {
        $service = new ArticleService();

        $categoryOptions = $service->getCategoryOptions();
        $sourceTypes = $service->getSourceTypes();
        $article = $service->getArticle($id);
        $xmTags = $service->getXmTags($id);

        $this->seo->prependTitle('编辑文章');

        $this->view->setVar('article', $article);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.article.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getArticleInfoUrl($id);
            return $this->response->redirect($location);
        }

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

        $this->seo->prependTitle(['专栏', $article['title']]);
        $this->seo->setDescription($article['summary']);

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
        $service = new ArticleCreateService();

        $article = $service->handle();

        if ($article->published == ArticleModel::PUBLISH_APPROVED) {
            $location = $this->url->get(['for' => 'home.article.show', 'id' => $article->id]);
            $msg = '发布文章成功';
        } else {
            $location = $this->url->get(['for' => 'home.uc.articles']);
            $msg = '创建文章成功，管理员审核后对外可见';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.article.update")
     */
    public function updateAction($id)
    {
        $service = new ArticleUpdateService();

        $article = $service->handle($id);

        if ($article->published == ArticleModel::PUBLISH_APPROVED) {
            $location = $this->url->get(['for' => 'home.article.show', 'id' => $article->id]);
            $msg = '更新文章成功';
        } else {
            $location = $this->url->get(['for' => 'home.uc.articles']);
            $msg = '更新文章成功，管理员审核后对外可见';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.article.delete")
     */
    public function deleteAction($id)
    {
        $service = new ArticleDeleteService();

        $service->handle($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除文章成功',
        ];

        return $this->jsonSuccess($content);
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
