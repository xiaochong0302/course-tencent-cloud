<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\PointHistory as PointHistoryService;

/**
 * @RoutePrefix("/admin/point/history")
 */
class PointHistoryController extends Controller
{

    /**
     * @Get("/search", name="admin.point_history.search")
     */
    public function searchAction()
    {
        $historyService = new PointHistoryService();

        $eventTypes = $historyService->getEventTypes();

        $this->view->setVar('event_types', $eventTypes);
    }

    /**
     * @Get("/list", name="admin.point_history.list")
     */
    public function listAction()
    {
        $historyService = new PointHistoryService();

        $pager = $historyService->getHistories();

        $this->view->setVar('pager', $pager);
    }

}