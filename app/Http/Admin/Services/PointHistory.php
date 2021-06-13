<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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