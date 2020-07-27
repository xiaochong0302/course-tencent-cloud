<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Order as OrderService;

/**
 * @RoutePrefix("/admin/order")
 */
class OrderController extends Controller
{

    /**
     * @Get("/search", name="admin.order.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.order.list")
     */
    public function listAction()
    {
        $orderService = new OrderService();

        $pager = $orderService->getOrders();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.order.show")
     */
    public function showAction($id)
    {
        $orderService = new OrderService();

        $order = $orderService->getOrder($id);
        $trades = $orderService->getTrades($order->id);
        $refunds = $orderService->getRefunds($order->id);
        $account = $orderService->getAccount($order->owner_id);
        $user = $orderService->getUser($order->owner_id);

        $this->view->setVar('order', $order);
        $this->view->setVar('trades', $trades);
        $this->view->setVar('refunds', $refunds);
        $this->view->setVar('account', $account);
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/statuses", name="admin.order.statuses")
     */
    public function statusesAction($id)
    {
        $orderService = new OrderService();

        $statuses = $orderService->getStatusHistory($id);

        $this->view->setVar('statuses', $statuses);
    }

}
