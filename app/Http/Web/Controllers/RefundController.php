<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Order\OrderInfo as OrderInfoService;
use App\Services\Frontend\Refund\RefundCancel as RefundCancelService;
use App\Services\Frontend\Refund\RefundConfirm as RefundConfirmService;
use App\Services\Frontend\Refund\RefundCreate as RefundCreateService;
use App\Services\Frontend\Refund\RefundInfo as RefundInfoService;

/**
 * @RoutePrefix("/refund")
 */
class RefundController extends Controller
{

    /**
     * @Get("/confirm", name="web.refund.confirm")
     */
    public function confirmAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        $service = new RefundConfirmService();

        $confirm = $service->handle($sn);

        $this->view->setVar('order', $order);
        $this->view->setVar('confirm', $confirm);
    }

    /**
     * @Post("/create", name="web.refund.create")
     */
    public function createAction()
    {
        $service = new RefundCreateService();

        $refund = $service->handle();

        $service = new RefundInfoService();

        $refund = $service->handle($refund->sn);

        return $this->jsonSuccess(['refund' => $refund]);
    }

    /**
     * @Get("/{sn:[0-9]+}/info", name="web.refund.info")
     */
    public function infoAction($sn)
    {
        $service = new RefundInfoService();

        $refund = $service->handle($sn);

        return $this->jsonSuccess(['refund' => $refund]);
    }

    /**
     * @Post("/{sn:[0-9]+}/cancel", name="web.refund.cancel")
     */
    public function cancelAction($sn)
    {
        $service = new RefundCancelService();

        $refund = $service->handle($sn);

        return $this->jsonSuccess(['refund' => $refund]);
    }

}
