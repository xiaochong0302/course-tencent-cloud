<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Review\ReviewCreate as ReviewCreateService;
use App\Services\Logic\Review\ReviewDelete as ReviewDeleteService;
use App\Services\Logic\Review\ReviewInfo as ReviewInfoService;
use App\Services\Logic\Review\ReviewLike as ReviewLikeService;
use App\Services\Logic\Review\ReviewUpdate as ReviewUpdateService;

/**
 * @RoutePrefix("/api/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.review.info")
     */
    public function infoAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/create", name="api.order.create")
     */
    public function createAction()
    {
        $service = new ReviewCreateService();

        $review = $service->handle();

        $service = new ReviewInfoService();

        $review = $service->handle($review->id);

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="api.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $service->handle($id);

        $service = new ReviewInfoService();

        $review = $service->handle($id);

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="api.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.review.like")
     */
    public function likeAction($id)
    {
        $service = new ReviewLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/unlike", name="api.review.unlike")
     */
    public function unlikeAction($id)
    {
        $service = new ReviewLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
