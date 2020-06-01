<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Trade as TradeService;
use App\Services\Frontend\Trade\TradeInfo as TradeInfoService;

/**
 * @RoutePrefix("/trade")
 */
class TradeController extends Controller
{

    /**
     * @Post("/create", name="web.trade.create")
     */
    public function createAction()
    {
        $service = new TradeService();

        $content = $service->create();

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/status", name="web.trade.status")
     */
    public function statusAction()
    {
        $sn = $this->request->getQuery('sn');

        $service = new TradeInfoService();

        $trade = $service->handle($sn);

        return $this->jsonSuccess(['status' => $trade['status']]);
    }

}
