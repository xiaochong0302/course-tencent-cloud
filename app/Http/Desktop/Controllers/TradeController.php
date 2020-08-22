<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\Trade as TradeService;
use App\Services\Frontend\Trade\TradeInfo as TradeInfoService;

/**
 * @RoutePrefix("/trade")
 */
class TradeController extends Controller
{

    /**
     * @Post("/create", name="desktop.trade.create")
     */
    public function createAction()
    {
        $service = new TradeService();

        $content = $service->create();

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/status", name="desktop.trade.status")
     */
    public function statusAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new TradeInfoService();

        $trade = $service->handle($sn);

        return $this->jsonSuccess(['status' => $trade['status']]);
    }

}
