<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\Order as OrderModel;
use App\Services\Logic\Order\OrderCancel as OrderCancelService;
use App\Services\Logic\Order\OrderConfirm as OrderConfirmService;
use App\Services\Logic\Order\OrderCreate as OrderCreateService;
use App\Services\Logic\Order\OrderInfo as OrderInfoService;
use App\Services\Logic\Order\PayProvider as PayProviderService;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/order")
 */
class OrderController extends Controller
{

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        if ($this->authUser->id == 0) {
            $dispatcher->forward([
                'controller' => 'account',
                'action' => 'login',
            ]);
            return false;
        }

        return true;
    }

    /**
     * @Get("/info", name="home.order.info")
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

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('order', $order);
    }

    /**
     * @Get("/confirm", name="home.order.confirm")
     */
    public function confirmAction()
    {
        $itemId = $this->request->getQuery('item_id', 'string');
        $itemType = $this->request->getQuery('item_type', 'string');

        $service = new OrderConfirmService();

        $confirm = $service->handle($itemId, $itemType);

        $this->seo->prependTitle('确认订单');

        $this->view->setVar('confirm', $confirm);
    }

    /**
     * @Post("/create", name="home.order.create")
     */
    public function createAction()
    {
        $service = new OrderCreateService();

        $order = $service->handle();

        $location = $this->url->get(
            ['for' => 'home.order.pay'],
            ['sn' => $order->sn]
        );

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Get("/pay", name="home.order.pay")
     */
    public function payAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new PayProviderService();

        $payProvider = $service->handle();

        $service = new OrderInfoService();

        $order = $service->handle($sn);

        if ($order['status'] != OrderModel::STATUS_PENDING) {
            return $this->response->redirect(['for' => 'home.uc.orders']);
        }

        $this->seo->prependTitle('支付订单');

        $this->view->setVar('pay_provider', $payProvider);
        $this->view->setVar('order', $order);
    }

    /**
     * @Post("/cancel", name="home.order.cancel")
     */
    public function cancelAction()
    {
        $sn = $this->request->getPost('sn', 'string');

        $service = new OrderCancelService();

        $order = $service->handle($sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
