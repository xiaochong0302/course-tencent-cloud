<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="desktop.page.show")
     */
    public function showAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        $this->seo->prependTitle(['单页', $page['title']]);

        $this->view->setVar('page', $page);
    }

}
