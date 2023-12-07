<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\TeacherConsult as TeacherConsultRepo;
use App\Services\Logic\Consult\ConsultList as ConsultListService;
use App\Services\Logic\Service as LogicService;

class ConsultList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();
        $params = $pagerQuery->getParams();

        $params['teacher_id'] = $user->id;
        $params['status'] = $params['status'] ?? null;

        if ($params['status'] == 'pending') {
            $params['replied'] = 0;
        } elseif ($params['status'] == 'replied') {
            $params['replied'] = 1;
        }

        $repo = new TeacherConsultRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    protected function handleConsults($pager)
    {
        $service = new ConsultListService();

        return $service->handleConsults($pager);
    }

}
