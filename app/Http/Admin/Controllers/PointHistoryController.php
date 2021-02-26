<?php

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

        $this->view->pick('point/history/search');
        $this->view->setVar('event_types', $eventTypes);
    }

    /**
     * @Get("/list", name="admin.point_history.list")
     */
    public function listAction()
    {
        $historyService = new PointHistoryService();

        $pager = $historyService->getHistories();

        $this->view->pick('point/history/list');

        $this->view->setVar('pager', $pager);
    }

}