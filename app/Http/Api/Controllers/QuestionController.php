<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Models\Question as QuestionModel;
use App\Services\Logic\Question\AnswerList as AnswerListService;
use App\Services\Logic\Question\CategoryList as CategoryListService;
use App\Services\Logic\Question\CommentList as CommentListService;
use App\Services\Logic\Question\QuestionDelete as QuestionDeleteService;
use App\Services\Logic\Question\QuestionFavorite as QuestionFavoriteService;
use App\Services\Logic\Question\QuestionInfo as QuestionInfoService;
use App\Services\Logic\Question\QuestionLike as QuestionLikeService;
use App\Services\Logic\Question\QuestionList as QuestionListService;

/**
 * @RoutePrefix("/api/question")
 */
class QuestionController extends Controller
{

    /**
     * @Get("/categories", name="api.question.categories")
     */
    public function categoriesAction()
    {
        $service = new CategoryListService();

        $categories = $service->handle();

        return $this->jsonSuccess(['categories' => $categories]);
    }

    /**
     * @Get("/list", name="api.question.list")
     */
    public function listAction()
    {
        $service = new QuestionListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.question.info")
     */
    public function infoAction($id)
    {
        $service = new QuestionInfoService();

        $question = $service->handle($id);

        if ($question['deleted'] == 1) {
            $this->notFound();
        }

        $approved = $question['published'] == QuestionModel::PUBLISH_APPROVED;
        $owned = $question['me']['owned'] == 1;

        if (!$approved && !$owned) {
            $this->notFound();
        }

        return $this->jsonSuccess(['question' => $question]);
    }

    /**
     * @Get("/{id:[0-9]+}/answers", name="api.question.answers")
     */
    public function answersAction($id)
    {
        $service = new AnswerListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="api.question.comments")
     */
    public function commentsAction($id)
    {
        $service = new CommentListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="api.question.delete")
     */
    public function deleteAction($id)
    {
        $service = new QuestionDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="api.question.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new QuestionFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.question.like")
     */
    public function likeAction($id)
    {
        $service = new QuestionLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
