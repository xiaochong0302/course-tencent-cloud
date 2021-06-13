<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Refund as RefundService;

/**
 * @RoutePrefix("/admin/refund")
 */
class RefundController extends Controller
{

    /**
     * @Get("/search", name="admin.refund.search")
     */
    public function searchAction()
    {
        $refundService = new RefundService();

        $statusTypes = $refundService->getStatusTypes();

        $this->view->setVar('status_types', $statusTypes);
    }

    /**
     * @Get("/list", name="admin.refund.list")
     */
    public function listAction()
    {
        $refundService = new RefundService();

        $pager = $refundService->getRefunds();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.refund.show")
     */
    public function showAction($id)
    {
        $refundService = new RefundService();

        $refund = $refundService->getRefund($id);
        $order = $refundService->getOrder($refund->order_id);
        $trade = $refundService->getTrade($refund->trade_id);
        $account = $refundService->getAccount($trade->owner_id);
        $user = $refundService->getUser($trade->owner_id);

        $this->view->setVar('refund', $refund);
        $this->view->setVar('order', $order);
        $this->view->setVar('trade', $trade);
        $this->view->setVar('account', $account);
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/status/history", name="admin.refund.status_history")
     */
    public function statusHistoryAction($id)
    {
        $refundService = new RefundService();

        $statusHistory = $refundService->getStatusHistory($id);

        $this->view->pick('refund/status_history');
        $this->view->setVar('status_history', $statusHistory);
    }

    /**
     * @Post("/{id:[0-9]+}/review", name="admin.refund.review")
     */
    public function reviewAction($id)
    {
        $refundService = new RefundService;

        $refundService->reviewRefund($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '审核退款成功',
        ];

        return $this->jsonSuccess($content);
    }

}
