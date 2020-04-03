<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Review as ReviewService;

/**
 * @RoutePrefix("/review")
 */
class ReviewController extends Controller
{

    /**
     * @Post("/create", name="web.review.create")
     */
    public function createAction()
    {
        $service = new ReviewService();

        $review = $service->create();

        $data = $service->getReview($review->id);

        return $this->jsonSuccess($data);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.review.show")
     */
    public function showAction($id)
    {
        $service = new ReviewService();

        $review = $service->getReview($id);

        return $this->response->ajaxSuccess($review);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewService();

        $review = $service->update($id);

        $data = $service->getReview($review->id);

        return $this->response->ajaxSuccess($data);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewService();

        $service->delete($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/agree", name="web.review.agree")
     */
    public function agreeAction($id)
    {
        $service = new ReviewService();

        $service->agree($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/oppose", name="web.review.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ReviewService();

        $service->oppose($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/reply", name="web.review.reply")
     */
    public function replyAction($id)
    {
        $service = new ReviewService();

        $service->reply($id);

        return $this->response->ajaxSuccess();
    }

}
