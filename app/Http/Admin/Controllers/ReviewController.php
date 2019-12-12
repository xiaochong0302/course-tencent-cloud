<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Review as ReviewService;

/**
 * @RoutePrefix("/admin/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/search", name="admin.review.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.review.list")
     */
    public function listAction()
    {
        $service = new ReviewService();

        $pager = $service->getReviews();

        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $course = null;

        if ($courseId > 0) {
            $course = $service->getCourse($courseId);
        }

        $this->view->setVar('pager', $pager);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id}/edit", name="admin.review.edit")
     */
    public function editAction($id)
    {
        $service = new ReviewService();

        $review = $service->getReview($id);

        $this->view->setVar('review', $review);
    }

    /**
     * @Post("/{id}/update", name="admin.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewService();

        $service->updateReview($id);

        $content = [
            'msg' => '更新评价成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewService();

        $service->deleteReview($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除评价成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.review.restore")
     */
    public function restoreAction($id)
    {
        $service = new ReviewService();

        $service->restoreReview($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原评价成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
