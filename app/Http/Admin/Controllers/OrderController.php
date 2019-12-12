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
     * @Get("/{id}/show", name="admin.order.show")
     */
    public function showAction($id)
    {
        $orderService = new OrderService();

        $order = $orderService->getOrder($id);
        $trades = $orderService->getTrades($order->sn);
        $refunds = $orderService->getRefunds($order->sn);
        $user = $orderService->getUser($order->user_id);

        $this->view->setVar('order', $order);
        $this->view->setVar('trades', $trades);
        $this->view->setVar('refunds', $refunds);
        $this->view->setVar('user', $user);
    }

    /**
     * @Post("/{id}/close", name="admin.order.close")
     */
    public function closeAction($id)
    {
        $orderService = new OrderService();

        $orderService->closeOrder($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '关闭订单成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/refund", name="admin.order.refund")
     */
    public function refundAction()
    {
        $tradeId = $this->request->getPost('trade_id', 'int');

        $orderService = new OrderService;

        $orderService->refundTrade($tradeId);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '订单退款成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
