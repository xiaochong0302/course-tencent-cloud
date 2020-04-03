<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\My as MyService;

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
        $service = new MyService();

        $courses = $service->getCourses();
        
        var_dump($courses);exit;

        $this->view->courses = $courses;
    }

    /**
     * @Get("/consults", name="web.my.consults")
     */
    public function consultsAction()
    {
        $service = new MyService();

        $consults = $service->getConsults();

        $this->view->consults = $consults;
    }

    /**
     * @Get("/reviews", name="web.my.reviews")
     */
    public function reviewsAction()
    {
        $service = new MyService();

        $reviews = $service->getReviews();

        $this->view->reviews = $reviews;
    }

    /**
     * @Get("/orders", name="web.my.orders")
     */
    public function ordersAction()
    {
        $service = new MyService();

        $orders = $service->getOrders();

        $this->view->orders = $orders;

        return $this->jsonSuccess($orders);
    }

    /**
     * @Get("/coupons", name="web.my.coupons")
     */
    public function couponsAction()
    {
        $service = new MyService();

        $coupons = $service->getCoupons();

        $this->view->coupons = $coupons;
    }

    /**
     * @Get("/balance", name="web.my.balance")
     */
    public function balanceAction()
    {
        
    }

}
