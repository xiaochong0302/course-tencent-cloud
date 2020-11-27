<?php

namespace App\Http\Api\Controllers;

use App\Http\Api\Services\Trade as TradeService;
use App\Services\Logic\Trade\TradeInfo as TradeInfoService;

/**
 * @RoutePrefix("/api/trade")
 */
class TradeController extends Controller
{

    /**
     * @Get("/info", name="api.trade.info")
     */
    public function infoAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new TradeInfoService();

        $trade = $service->handle($sn);

        return $this->jsonSuccess(['trade' => $trade]);
    }

    /**
     * @Get("/h5/pay", name="api.trade.h5_pay")
     */
    public function h5PayAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new TradeService();

        $response = $service->h5Pay($sn);

        if (!$response) {
            echo "H5支付跳转失败，请回退重试";
        }

        $response->send();

        exit();
    }

    /**
     * @Post("/h5/create", name="api.trade.h5_create")
     */
    public function createH5TradeAction()
    {
        $service = new TradeService();

        $trade = $service->createH5Trade();

        $service = new TradeInfoService();

        $trade = $service->handle($trade->sn);

        return $this->jsonSuccess(['trade' => $trade]);
    }

    /**
     * @Post("/mp/create", name="api.trade.mp_create")
     */
    public function createMpTradeAction()
    {
        $service = new TradeService();

        $content = $service->createMpTrade();

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/app/create", name="api.trade.app_create")
     */
    public function createAppTradeAction()
    {
        $service = new TradeService();

        $content = $service->createMpTrade();

        return $this->jsonSuccess($content);
    }

}