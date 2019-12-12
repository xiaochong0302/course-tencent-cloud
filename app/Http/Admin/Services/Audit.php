<?php

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

        $pager = $auditRepo->paginate($params, $sort, $page, $limit);

        return $pager;
    }

    public function getAudit($id)
    {
        $auditRepo = new AuditRepo();

        $audit = $auditRepo->findById($id);

        return $audit;
    }

}
