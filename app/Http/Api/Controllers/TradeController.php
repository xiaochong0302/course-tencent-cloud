<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        if ($trade['deleted'] == 1) {
            $this->notFound();
        }

        if ($trade['me']['owned'] == 0) {
            $this->forbidden();
        }

        return $this->jsonSuccess(['trade' => $trade]);
    }

    /**
     * @Post("/h5/create", name="api.trade.h5_create")
     */
    public function createH5TradeAction()
    {
        $service = new TradeService();

        $content = $service->createH5Trade();

        return $this->jsonSuccess($content);
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
     * @Post("/mini/create", name="api.trade.mini_create")
     */
    public function createMiniTradeAction()
    {
        $service = new TradeService();

        $content = $service->createMiniTrade();

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/app/create", name="api.trade.app_create")
     */
    public function createAppTradeAction()
    {
        $service = new TradeService();

        $content = $service->createAppTrade();

        return $this->jsonSuccess($content);
    }

}