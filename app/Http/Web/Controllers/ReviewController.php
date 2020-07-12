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

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $review = $service->handle($id);

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
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
