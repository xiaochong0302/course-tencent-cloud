<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Help\HelpInfo as HelpInfoService;
use App\Services\Logic\Help\HelpList as HelpListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;

/**
 * @RoutePrefix("/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/", name="home.help.index")
     */
    public function indexAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getHelpIndexUrl();
            return $this->response->redirect($location);
        }

        $service = new HelpListService();

        $items = $service->handle();

        $this->seo->prependTitle('帮助');

        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.help.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getHelpInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new HelpInfoService();

        $help = $service->handle($id);

        if ($help['deleted'] == 1) {
            $this->notFound();
        }

        if ($help['published'] == 0) {
            $this->notFound();
        }

        $this->seo->prependTitle(['帮助', $help['title']]);

        $this->view->setVar('help', $help);
    }

}
