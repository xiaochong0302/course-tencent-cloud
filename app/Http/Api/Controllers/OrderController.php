<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Order\OrderCancel as OrderCancelService;
use App\Services\Logic\Order\OrderConfirm as OrderConfirmService;
use App\Services\Logic\Order\OrderCreate as OrderCreateService;
use App\Services\Logic\Order\OrderInfo as OrderInfoService;

/**
 * @RoutePrefix("/api/order")
 */
class OrderController extends Controller
{

    /**
     * @Get("/info", name="api.order.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        if ($order['deleted'] == 1) {
            $this->notFound();
        }

        if ($order['me']['owned'] == 0) {
            $this->forbidden();
        }

        return $this->jsonSuccess(['order' => $order]);
    }

    /**
     * @Get("/confirm", name="api.order.confirm")
     */
    public function confirmAction()
    {
        $itemId = $this->request->getQuery('item_id', 'int');
        $itemType = $this->request->getQuery('item_type', 'int');

        $service = new OrderConfirmService();

        $confirm = $service->handle($itemId, $itemType);

        return $this->jsonSuccess(['confirm' => $confirm]);

    }

    /**
     * @Post("/create", name="api.order.create")
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
     * @Post("/cancel", name="api.order.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn', 'string');

        $service = new OrderCancelService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
