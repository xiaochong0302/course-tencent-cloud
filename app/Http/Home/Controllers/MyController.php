<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\My as MyService;

/**
 * @RoutePrefix("/my")
 */
class MyController extends Controller
{

    /**
     * @Get("/courses", name="home.my.courses")
     */
    public function coursesAction()
    {
        $service = new MyService();

        $courses = $service->getCourses();
        
        var_dump($courses);exit;

        $this->view->courses = $courses;
    }

    /**
     * @Get("/consults", name="home.my.consults")
     */
    public function consultsAction()
    {
        $service = new MyService();

        $consults = $service->getConsults();

        $this->view->consults = $consults;
    }

    /**
     * @Get("/reviews", name="home.my.reviews")
     */
    public function reviewsAction()
    {
        $service = new MyService();

        $reviews = $service->getReviews();

        $this->view->reviews = $reviews;
    }

    /**
     * @Get("/orders", name="home.my.orders")
     */
    public function ordersAction()
    {
        $service = new MyService();

        $orders = $service->getOrders();

        $this->view->orders = $orders;

        return $this->jsonSuccess($orders);
    }

    /**
     * @Get("/coupons", name="home.my.coupons")
     */
    public function couponsAction()
    {
        $service = new MyService();

        $coupons = $service->getCoupons();

        $this->view->coupons = $coupons;
    }

    /**
     * @Get("/balance", name="home.my.balance")
     */
    public function balanceAction()
    {
        
    }

}
