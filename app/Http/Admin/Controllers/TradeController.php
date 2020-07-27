<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Trade as TradeService;

/**
 * @RoutePrefix("/admin/trade")
 */
class TradeController extends Controller
{

    /**
     * @Get("/search", name="admin.trade.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.trade.list")
     */
    public function listAction()
    {
        $tradeService = new TradeService();

        $pager = $tradeService->getTrades();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.trade.show")
     */
    public function showAction($id)
    {
        $tradeService = new TradeService();

        $trade = $tradeService->getTrade($id);
        $refunds = $tradeService->getRefunds($trade->id);
        $order = $tradeService->getOrder($trade->order_id);
        $account = $tradeService->getAccount($trade->owner_id);
        $user = $tradeService->getUser($trade->owner_id);

        $this->view->setVar('refunds', $refunds);
        $this->view->setVar('trade', $trade);
        $this->view->setVar('order', $order);
        $this->view->setVar('account', $account);
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/statuses", name="admin.trade.statuses")
     */
    public function statusesAction($id)
    {
        $tradeService = new TradeService();

        $statuses = $tradeService->getStatusHistory($id);

        $this->view->setVar('statuses', $statuses);
    }

    /**
     * @Post("/{id:[0-9]+}/refund", name="admin.trade.refund")
     */
    public function refundAction($id)
    {
        $tradeService = new TradeService();

        $tradeService->refundTrade($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '申请退款成功，请到退款管理中审核确认',
        ];

        return $this->jsonSuccess($content);
    }

}
