<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Danmu\DanmuCreate as DanmuCreateService;
use App\Services\Frontend\Danmu\DanmuInfo as DanmuInfoService;

/**
 * @RoutePrefix("/danmu")
 */
class DanmuController extends Controller
{

    /**
     * @Post("/create", name="desktop.danmu.create")
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
