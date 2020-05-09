<?php

namespace App\Http\Web\Controllers;

/**
 * @RoutePrefix("/order")
 */
class OrderController extends Controller
{

    /**
     * @Get("/confirm", name="web.order.confirm")
     */
    public function confirmAction()
    {

    }

    /**
     * @Post("/create", name="web.order.create")
     */
    public function createAction()
    {

    }

    /**
     * @Get("/cashier", name="web.order.cashier")
     */
    public function cashierAction()
    {

    }

    /**
     * @Post("/pay", name="web.order.pay")
     */
    public function payAction()
    {

    }

    /**
     * @Post("/notify/{channel}", name="web.order.notify")
     */
    public function notifyAction($channel)
    {

    }

    /**
     * @Post("/status", name="web.order.status")
     */
    public function statusAction()
    {

    }

    /**
     * @Post("/cancel", name="web.order.cancel")
     */
    public function cancelAction()
    {

    }

}
