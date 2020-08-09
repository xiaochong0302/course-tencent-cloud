<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Help\HelpInfo as HelpInfoService;
use App\Services\Frontend\Help\HelpList as HelpListService;

/**
 * @RoutePrefix("/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/", name="web.help.index")
     */
    public function indexAction()
    {
        $service = new HelpListService();

        $helps = $service->handle();

        $this->seo->prependTitle('帮助');

        $this->view->setVar('helps', $helps);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.help.show")
     */
    public function showAction($id)
    {
        $service = new HelpInfoService();

        $help = $service->handle($id);

        $this->seo->prependTitle(['帮助', $help['title']]);

        $this->view->setVar('help', $help);
    }

}
