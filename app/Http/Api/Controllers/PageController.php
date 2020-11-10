<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/api/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="api.page.info")
     */
    public function infoAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        return $this->jsonSuccess(['page' => $page]);
    }

}
