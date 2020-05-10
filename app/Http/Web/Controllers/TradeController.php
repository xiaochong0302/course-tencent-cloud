<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Trade\TradeCreate as TradeCreateService;
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
        $service = new TradeCreateService();

        $result = $service->handle();

        return $this->jsonSuccess([
            'trade_sn' => $result['trade_sn'],
            'code_url' => $result['code_url'],
        ]);
    }

    /**
     * @Get("/{sn:[0-9]+}/status", name="web.trade.status")
     */
    public function statusAction($sn)
    {
        $service = new TradeInfoService();

        $trade = $service->handle($sn);

        return $this->jsonSuccess(['status' => $trade->status]);
    }

}
