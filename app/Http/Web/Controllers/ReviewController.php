<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Review\ReviewCreate as ReviewCreateService;
use App\Services\Frontend\Review\ReviewDelete as ReviewDeleteService;
use App\Services\Frontend\Review\ReviewInfo as ReviewInfoService;
use App\Services\Frontend\Review\ReviewLike as ReviewLikeService;
use App\Services\Frontend\Review\ReviewUpdate as ReviewUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/add", name="web.review.add")
     */
    public function addAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="web.review.edit")
     */
    public function editAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('review', $review);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="web.review.info")
     */
    public function infoAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/create", name="web.review.create")
     */
    public function createAction()
    {
        $service = new ReviewCreateService();

        $review = $service->handle();

        $service = new ReviewInfoService();

        $review = $service->handle($review->id);

        $content = [
            'review' => $review,
            'msg' => '发布评价成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $service->handle($id);

        $service = new ReviewInfoService();

        $review = $service->handle($id);

        $content = [
            'review' => $review,
            'msg' => '更新评价成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewDeleteService();

        $service->handle($id);

        $content = ['msg' => '删除评价成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="web.review.like")
     */
    public function likeAction($id)
    {
        $service = new ReviewLikeService();

        $like = $service->handle($id);

        $msg = $like->deleted == 0 ? '点赞成功' : '取消点赞成功';

        $content = ['msg' => $msg];

        return $this->jsonSuccess($content);
    }

}
