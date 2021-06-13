<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Audit as AuditRepo;

class Audit extends Service
{

    public function getAudits()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $auditRepo = new AuditRepo();

        return $auditRepo->paginate($params, $sort, $page, $limit);
    }

    public function getAudit($id)
    {
        $auditRepo = new AuditRepo();

        return $auditRepo->findById($id);
    }

}
