<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Danmu\DanmuCreate as DanmuCreateService;
use App\Services\Logic\Danmu\DanmuInfo as DanmuInfoService;

/**
 * @RoutePrefix("/danmu")
 */
class DanmuController extends Controller
{

    /**
     * @Post("/create", name="home.danmu.create")
     */
    public function createAction()
    {
        $service = new DanmuCreateService();

        $danmu = $service->handle();

        $service = new DanmuInfoService();

        $danmu = $service->handle($danmu->id);

        return $this->jsonSuccess(['danmu' => $danmu]);
    }

}
