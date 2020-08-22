<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Help\HelpInfo as HelpInfoService;
use App\Services\Frontend\Help\HelpList as HelpListService;

/**
 * @RoutePrefix("/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/", name="desktop.help.index")
     */
    public function indexAction()
    {
        $service = new HelpListService();

        $items = $service->handle();

        $this->seo->prependTitle('帮助');

        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/{id:[0-9]+}", name="desktop.help.show")
     */
    public function showAction($id)
    {
        $service = new HelpInfoService();

        $help = $service->handle($id);

        $this->seo->prependTitle(['帮助', $help['title']]);

        $this->view->setVar('help', $help);
    }

}
