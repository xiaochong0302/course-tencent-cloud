<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\FlashSale\OrderCreate as OrderCreateService;
use App\Services\Logic\FlashSale\SaleList as SaleListService;
use App\Services\Logic\Order\OrderInfo as OrderInfoService;

/**
 * @RoutePrefix("/api/flash/sale")
 */
class FlashSaleController extends Controller
{

    /**
     * @Get("/list", name="api.flash_sale.list")
     */
    public function listAction()
    {
        $service = new SaleListService();

        $sales = $service->handle();

        return $this->jsonSuccess(['sales' => $sales]);
    }

    /**
     * @Post("/order", name="api.flash_sale.order")
     */
    public function orderAction()
    {
        $service = new OrderCreateService();

        $order = $service->handle();

        $service = new OrderInfoService();

        $order = $service->handle($order->sn);

        return $this->jsonSuccess(['order' => $order]);
    }

}
