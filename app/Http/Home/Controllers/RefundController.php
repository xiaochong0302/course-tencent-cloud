<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Order\OrderInfo as OrderInfoService;
use App\Services\Logic\Refund\RefundCancel as RefundCancelService;
use App\Services\Logic\Refund\RefundConfirm as RefundConfirmService;
use App\Services\Logic\Refund\RefundCreate as RefundCreateService;
use App\Services\Logic\Refund\RefundInfo as RefundInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/refund")
 */
class RefundController extends Controller
{

    /**
     * @Get("/confirm", name="home.refund.confirm")
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
     * @Post("/create", name="home.refund.create")
     */
    public function createAction()
    {
        $service = new RefundCreateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.refunds']);

        $content = [
            'location' => $location,
            'target' => 'parent',
            'msg' => '提交申请成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/info", name="home.refund.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new RefundInfoService();

        $refund = $service->handle($sn);

        if ($refund['deleted'] == 1) {
            $this->notFound();
        }

        if ($refund['me']['owned'] == 0) {
            $this->forbidden();
        }

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('refund', $refund);
    }

    /**
     * @Post("/cancel", name="home.refund.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn', 'string');

        $service = new RefundCancelService();

        $service->handle($sn);

        return $this->jsonSuccess(['msg' => '取消退款成功']);
    }

}
