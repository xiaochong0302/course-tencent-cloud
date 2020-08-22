<?php

namespace App\Http\Desktop\Controllers;

use App\Models\Order as OrderModel;
use App\Services\Frontend\Order\OrderCancel as OrderCancelService;
use App\Services\Frontend\Order\OrderConfirm as OrderConfirmService;
use App\Services\Frontend\Order\OrderCreate as OrderCreateService;
use App\Services\Frontend\Order\OrderInfo as OrderInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/order")
 */
class OrderController extends Controller
{

    /**
     * @Get("/info", name="desktop.order.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('order', $order);
    }

    /**
     * @Get("/confirm", name="desktop.order.confirm")
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
     * @Post("/create", name="desktop.order.create")
     */
    public function createAction()
    {
        $service = new OrderCreateService();

        $order = $service->handle();

        $location = $this->url->get(['for' => 'desktop.order.pay'], ['sn' => $order->sn]);

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Get("/pay", name="desktop.order.pay")
     */
    public function payAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        if ($order['status'] != OrderModel::STATUS_PENDING) {
            $this->response->redirect(['for' => 'desktop.my.orders']);
        }

        $this->view->setVar('order', $order);
    }

    /**
     * @Post("/cancel", name="desktop.order.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn');

        $service = new OrderCancelService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
