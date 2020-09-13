<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Order\OrderInfo as OrderInfoService;
use App\Services\Frontend\Refund\RefundCancel as RefundCancelService;
use App\Services\Frontend\Refund\RefundConfirm as RefundConfirmService;
use App\Services\Frontend\Refund\RefundCreate as RefundCreateService;
use App\Services\Frontend\Refund\RefundInfo as RefundInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/refund")
 */
class RefundController extends Controller
{

    /**
     * @Get("/confirm", name="desktop.refund.confirm")
     */
    public function confirmAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        $service = new RefundConfirmService();

        $confirm = $service->handle($sn);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('order', $order);
        $this->view->setVar('confirm', $confirm);
    }

    /**
     * @Post("/create", name="desktop.refund.create")
     */
    public function createAction()
    {
        $service = new RefundCreateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'desktop.my.refunds']),
            'msg' => '申请退款成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/info", name="desktop.refund.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new RefundInfoService();

        $refund = $service->handle($sn);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('refund', $refund);
    }

    /**
     * @Post("/cancel", name="desktop.refund.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn', 'string');

        $service = new RefundCancelService();

        $service->handle($sn);

        $content = [
            'location' => $this->url->get(['for' => 'desktop.my.refunds']),
            'msg' => '取消退款成功',
        ];

        return $this->jsonSuccess($content);
    }

}
