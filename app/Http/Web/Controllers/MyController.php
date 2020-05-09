<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\My\ConsultList as MyConsultListService;
use App\Services\Frontend\My\CourseList as MyCourseListService;
use App\Services\Frontend\My\FavoriteList as MyFavoriteListService;
use App\Services\Frontend\My\OrderList as MyOrderListService;
use App\Services\Frontend\My\RefundList as MyRefundListService;
use App\Services\Frontend\My\ReviewList as MyReviewListService;

/**
 * @RoutePrefix("/my")
 */
class MyController extends Controller
{

    /**
     * @Get("/courses", name="web.my.courses")
     */
    public function coursesAction()
    {
        $service = new MyCourseListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/favorites", name="web.my.favorites")
     */
    public function favoritesAction()
    {
        $service = new MyFavoriteListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="web.my.consults")
     */
    public function consultsAction()
    {
        $service = new MyConsultListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/reviews", name="web.my.reviews")
     */
    public function reviewsAction()
    {
        $service = new MyReviewListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/orders", name="web.my.orders")
     */
    public function ordersAction()
    {
        $service = new MyOrderListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/refunds", name="web.my.refunds")
     */
    public function refundsAction()
    {
        $service = new MyRefundListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

}
