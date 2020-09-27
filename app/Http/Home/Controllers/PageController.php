<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.page.show")
     */
    public function showAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        $this->seo->prependTitle(['单页', $page['title']]);

        $this->view->setVar('page', $page);
    }

}
