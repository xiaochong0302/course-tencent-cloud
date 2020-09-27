<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Consult as ConsultService;

/**
 * @RoutePrefix("/admin/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/search", name="admin.consult.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.consult.list")
     */
    public function listAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $consultService = new ConsultService();

        $pager = $consultService->getConsults();

        $course = null;

        if ($courseId > 0) {
            $course = $consultService->getCourse($courseId);
        }

        $this->view->setVar('pager', $pager);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.consult.edit")
     */
    public function editAction($id)
    {
        $consultService = new ConsultService();

        $consult = $consultService->getConsult($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.consult.update")
     */
    public function updateAction($id)
    {
        $consultService = new ConsultService();

        $consultService->updateConsult($id);

        $content = [
            'msg' => '更新咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.consult.delete")
     */
    public function deleteAction($id)
    {
        $consultService = new ConsultService();

        $consultService->deleteConsult($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.consult.restore")
     */
    public function restoreAction($id)
    {
        $consultService = new ConsultService();

        $consultService->restoreConsult($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

}
