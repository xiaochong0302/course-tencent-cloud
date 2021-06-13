<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Stat as StatService;

/**
 * @RoutePrefix("/admin/stat")
 */
class StatController extends Controller
{

    /**
     * @Get("/sales/hot", name="admin.stat.hot_sales")
     */
    public function hotSalesAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $items = $statService->hotSales();

        $this->view->pick('stat/hot_sales');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/sales", name="admin.stat.sales")
     */
    public function salesAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->sales();

        $this->view->pick('stat/sales');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

    /**
     * @Get("/refunds", name="admin.stat.refunds")
     */
    public function refundsAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->refunds();

        $this->view->pick('stat/refunds');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

    /**
     * @Get("/users/registered", name="admin.stat.reg_users")
     */
    public function registeredUsersAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->registeredUsers();

        $this->view->pick('stat/registered_users');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

    /**
     * @Get("/users/online", name="admin.stat.online_users")
     */
    public function onlineUsersAction()
    {
        $statService = new StatService();

        $years = $statService->getYearOptions();
        $months = $statService->getMonthOptions();
        $data = $statService->onlineUsers();

        $this->view->pick('stat/online_users');
        $this->view->setVar('years', $years);
        $this->view->setVar('months', $months);
        $this->view->setVar('data', $data);
    }

}
