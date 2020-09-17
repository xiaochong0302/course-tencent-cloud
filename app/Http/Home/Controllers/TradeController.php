<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Trade as TradeService;
use App\Services\Logic\Trade\TradeInfo as TradeInfoService;

/**
 * @RoutePrefix("/trade")
 */
class TradeController extends Controller
{

    /**
     * @Post("/create", name="home.trade.create")
     */
    public function createAction()
    {
        $service = new TradeService();

        $content = $service->create();

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/status", name="home.trade.status")
     */
    public function statusAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $service = new TradeInfoService();

        $trade = $service->handle($sn);

        return $this->jsonSuccess(['status' => $trade['status']]);
    }

}
