<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Index as IndexService;
use App\Library\AppInfo;
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

        $appInfo = new AppInfo();

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

    }

}
