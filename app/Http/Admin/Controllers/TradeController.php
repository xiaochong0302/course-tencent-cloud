<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Trade as TradeService;
use Phalcon\Mvc\View;

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
        $tradeService = new TradeService();

        $channelTypes = $tradeService->getChannelTypes();
        $statusTypes = $tradeService->getStatusTypes();

        $this->view->setVar('channel_types', $channelTypes);
        $this->view->setVar('status_types', $statusTypes);
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
     * @Get("/{id:[0-9]+}/status/history", name="admin.trade.status_history")
     */
    public function statusHistoryAction($id)
    {
        $tradeService = new TradeService();

        $statusHistory = $tradeService->getStatusHistory($id);

        $this->view->pick('trade/status_history');
        $this->view->setVar('status_history', $statusHistory);
    }

    /**
     * @Route("/{id:[0-9]+}/refund", name="admin.trade.refund")
     */
    public function refundAction($id)
    {
        $tradeService = new TradeService();

        if ($this->request->isPost()) {

            $tradeService->refundTrade($id);

            return $this->jsonSuccess(['msg' => '提交申请成功']);
        }

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $trade = $tradeService->getTrade($id);
        $confirm = $tradeService->confirmRefund($id);

        $this->view->setVar('trade', $trade);
        $this->view->setVar('confirm', $confirm);
    }

}
