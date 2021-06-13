<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Audit as AuditService;

/**
 * @RoutePrefix("/admin/audit")
 */
class AuditController extends Controller
{

    /**
     * @Get("/search", name="admin.audit.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.audit.list")
     */
    public function listAction()
    {
        $auditService = new AuditService();

        $pager = $auditService->getAudits();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.audit.show")
     */
    public function showAction($id)
    {
        $auditService = new AuditService();

        $audit = $auditService->getAudit($id);

        $region = kg_ip2region($audit->user_ip);

        $this->view->setVar('audit', $audit);
        $this->view->setVar('region', $region);
    }

}
