<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Index as IndexService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin")
 */
class IndexController extends Controller
{

    /**
     * @Get("/", name="admin.index")
     */
    public function indexAction()
    {
        $indexService = new IndexService();

        $topMenus = $indexService->getTopMenus();
        $leftMenus = $indexService->getLeftMenus();
        $appInfo = $indexService->getAppInfo();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('top_menus', $topMenus);
        $this->view->setVar('left_menus', $leftMenus);
    }

    /**
     * @Get("/main", name="admin.main")
     */
    public function mainAction()
    {
        $indexService = new IndexService();

        $statInfo = $indexService->getStatInfo();
        $appInfo = $indexService->getAppInfo();
        $serverInfo = $indexService->getServerInfo();

        $this->view->setVar('stat_info', $statInfo);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('server_info', $serverInfo);
    }

}
