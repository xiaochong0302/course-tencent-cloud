<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Review\ReviewCreate as ReviewCreateService;
use App\Services\Frontend\Review\ReviewDelete as ReviewDeleteService;
use App\Services\Frontend\Review\ReviewInfo as ReviewInfoService;
use App\Services\Frontend\Review\ReviewLike as ReviewLikeService;
use App\Services\Frontend\Review\ReviewUpdate as ReviewUpdateService;

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
        $courseId = $this->request->getQuery('course_id');

        $this->view->setVar('course_id', $courseId);
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
            'msg' => '发布课程评价成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $review = $service->handle($id);

        $content = [
            'review' => $review,
            'msg' => '更新课程评价成功',
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

        $content = ['msg' => '删除课程评价成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="web.review.like")
     */
    public function likeAction($id)
    {
        $service = new ReviewLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
