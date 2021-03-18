<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\FlashSale\OrderCreate as OrderCreateService;
use App\Services\Logic\FlashSale\SaleList as SaleListService;
use Phalcon\Mvc\Dispatcher;

/**
 * @RoutePrefix("/flash/sale")
 */
class FlashSaleController extends Controller
{

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'home.account.login']);
            return false;
        }

        return true;
    }

    /**
     * @Get("/", name="home.flash_sale.index")
     */
    public function indexAction()
    {
        $service = new SaleListService();

        $sales = $service->handle();

        $this->view->setVar('sales', $sales);
    }

    /**
     * @Post("/order", name="home.flash_sale.order")
     */
    public function orderAction()
    {
        $service = new OrderCreateService();

        $order = $service->handle();

        $location = $this->url->get(
            ['for' => 'home.order.pay'],
            ['sn' => $order->sn]
        );

        return $this->jsonSuccess(['location' => $location]);
    }

}
