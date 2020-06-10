<?php

namespace App\Http\Web\Controllers;

use App\Models\Order as OrderModel;
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
     * @Get("/info", name="web.order.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        $this->view->setVar('order', $order);
    }

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

        $location = $this->url->get(['for' => 'web.order.pay'], ['sn' => $order->sn]);

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Get("/pay", name="web.order.pay")
     */
    public function payAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        if ($order['status'] != OrderModel::STATUS_PENDING) {
            $this->response->redirect(['for' => 'web.my.orders']);
        }

        $this->view->setVar('order', $order);
    }

    /**
     * @Post("/cancel", name="web.order.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn');

        $service = new OrderCancelService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
