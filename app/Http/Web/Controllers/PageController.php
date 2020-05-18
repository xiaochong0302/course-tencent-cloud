<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.page.show")
     */
    public function showAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        $this->view->setVar('page', $page);
    }

}
