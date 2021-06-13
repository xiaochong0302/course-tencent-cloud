<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
