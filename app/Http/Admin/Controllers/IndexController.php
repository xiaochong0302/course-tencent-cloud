<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $siteInfo = $indexService->getSiteInfo();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('site_info', $siteInfo);
        $this->view->setVar('top_menus', $topMenus);
        $this->view->setVar('left_menus', $leftMenus);
    }

    /**
     * @Get("/main", name="admin.main")
     */
    public function mainAction()
    {
        $indexService = new IndexService();

        $globalStat = $indexService->getGlobalStat();
        $todayStat = $indexService->getTodayStat();
        $modStat = $indexService->getModerationStat();
        $reportStat = $indexService->getReportStat();
        $appInfo = $indexService->getAppInfo();
        $serverInfo = $indexService->getServerInfo();

        $this->view->setVar('global_stat', $globalStat);
        $this->view->setVar('today_stat', $todayStat);
        $this->view->setVar('report_stat', $reportStat);
        $this->view->setVar('mod_stat', $modStat);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('server_info', $serverInfo);
    }

    /**
     * @Get("/releases", name="admin.releases")
     */
    public function releasesAction()
    {
        $indexService = new IndexService();

        $releases = $indexService->getReleases();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('releases', $releases);
    }

    /**
     * @Get("/phpinfo", name="admin.phpinfo")
     */
    public function phpinfoAction()
    {
        echo phpinfo();

        exit;
    }

}
