<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Help\HelpInfo as HelpInfoService;
use App\Services\Logic\Help\HelpList as HelpListService;

/**
 * @RoutePrefix("/api/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/list", name="api.help.list")
     */
    public function listAction()
    {
        $service = new HelpListService();

        $helps = $service->handle();

        return $this->jsonSuccess(['helps' => $helps]);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.help.info")
     */
    public function infoAction($id)
    {
        $service = new HelpInfoService();

        $help = $service->handle($id);

        return $this->jsonSuccess(['help' => $help]);
    }

}
