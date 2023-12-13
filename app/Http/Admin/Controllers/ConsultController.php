<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $consultService = new ConsultService();

        $publishTypes = $consultService->getPublishTypes();
        $xmCourses = $consultService->getXmCourses();

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('xm_courses', $xmCourses);
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

        $publishTypes = $consultService->getPublishTypes();

        $consult = $consultService->getConsult($id);

        $this->view->setVar('publish_types', $publishTypes);
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

    /**
     * @Route("/{id:[0-9]+}/moderate", name="admin.consult.moderate")
     */
    public function moderateAction($id)
    {
        $consultService = new ConsultService();

        if ($this->request->isPost()) {

            $consultService->moderate($id);

            $location = $this->url->get(['for' => 'admin.mod.consults']);

            $content = [
                'location' => $location,
                'msg' => '审核咨询成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $consultService->getReasons();
        $consult = $consultService->getConsultInfo($id);

        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/moderate/batch", name="admin.consult.batch_moderate")
     */
    public function batchModerateAction()
    {
        $consultService = new ConsultService();

        $consultService->batchModerate();

        $location = $this->url->get(['for' => 'admin.mod.consults']);

        $content = [
            'location' => $location,
            'msg' => '批量审核成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/delete/batch", name="admin.consult.batch_delete")
     */
    public function batchDeleteAction()
    {
        $consultService = new ConsultService();

        $consultService->batchDelete();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '批量删除成功',
        ];

        return $this->jsonSuccess($content);
    }

}
