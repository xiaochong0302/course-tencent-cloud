<?php

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

        $this->view->setVar('audit', $audit);
    }

}
