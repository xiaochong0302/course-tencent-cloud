<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Review as ReviewService;

/**
 * @RoutePrefix("/review")
 */
class ReviewController extends Controller
{

    /**
     * @Post("/create", name="home.review.create")
     */
    public function createAction()
    {
        $service = new ReviewService();

        $review = $service->create();

        $data = $service->getReview($review->id);

        return $this->ajaxSuccess($data);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.review.show")
     */
    public function showAction($id)
    {
        $service = new ReviewService();

        $review = $service->getReview($id);

        return $this->response->ajaxSuccess($review);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewService();

        $review = $service->update($id);

        $data = $service->getReview($review->id);

        return $this->response->ajaxSuccess($data);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewService();

        $service->delete($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/agree", name="home.review.agree")
     */
    public function agreeAction($id)
    {
        $service = new ReviewService();

        $service->agree($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/oppose", name="home.review.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ReviewService();

        $service->oppose($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/reply", name="home.review.reply")
     */
    public function replyAction($id)
    {
        $service = new ReviewService();

        $service->reply($id);

        return $this->response->ajaxSuccess();
    }

}
