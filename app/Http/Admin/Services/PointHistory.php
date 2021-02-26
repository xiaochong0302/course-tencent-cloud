<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointHistory as PointHistoryModel;
use App\Repos\PointHistory as PointHistoryRepo;

class PointHistory extends Service
{

    public function getHistories()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $historyRepo = new PointHistoryRepo();

        return $historyRepo->paginate($params, $sort, $page, $limit);
    }

    public function getEventTypes()
    {
        return PointHistoryModel::eventTypes();
    }

}