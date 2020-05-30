<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Order\OrderCancel as OrderCancelService;
use App\Services\Frontend\Order\OrderConfirm as OrderConfirmService;
use App\Services\Frontend\Order\OrderCreate as OrderCreateService;
use App\Services\Frontend\Order\OrderInfo as OrderInfoService;

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
        $itemId = $this->request->getQuery('item_id');
        $itemType = $this->request->getQuery('item_type');

        $service = new OrderConfirmService();

        $confirm = $service->handle($itemId, $itemType);

        $this->view->setVar('confirm', $confirm);
    }

    /**
     * @Post("/create", name="web.order.create")
     */
    public function createAction()
    {
        $service = new OrderCreateService();

        $order = $service->handle();

        $service = new OrderInfoService();

        $order = $service->handle($order->sn);

        return $this->jsonSuccess(['order' => $order]);
    }

    /**
     * @Get("/{sn:[0-9]+}/pay", name="web.order.pay")
     */
    public function payAction($sn)
    {
        $service = new OrderInfoService();

        $order = $service->handle($sn);

        $this->view->setVar('order', $order);
    }

    /**
     * @Get("/{sn:[0-9]+}/info", name="web.order.info")
     */
    public function infoAction($sn)
    {
        $service = new OrderInfoService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

    /**
     * @Post("/{sn:[0-9]+}/cancel", name="web.order.cancel")
     */
    public function cancelAction($sn)
    {
        $service = new OrderCancelService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
