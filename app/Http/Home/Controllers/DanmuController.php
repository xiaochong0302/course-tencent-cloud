<?php

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
