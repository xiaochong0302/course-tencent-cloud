<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Package as PackageService;

/**
 * @RoutePrefix("/admin/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/guiding", name="admin.package.guiding")
     */
    public function guidingAction()
    {
        $xmCourseIds = $this->request->getQuery('xm_course_ids');

        $packageService = new PackageService();

        $courses = $packageService->getGuidingCourses($xmCourseIds);
        $guidingPrice = $packageService->getGuidingPrice($courses);

        $this->view->setVar('courses', $courses);
        $this->view->setVar('guiding_price', $guidingPrice);
    }

    /**
     * @Get("/list", name="admin.package.list")
     */
    public function listAction()
    {
        $packageService = new PackageService();

        $pager = $packageService->getPackages();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.package.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.package.create")
     */
    public function createAction()
    {
        $packageService = new PackageService();

        $package = $packageService->createPackage();

        $location = $this->url->get([
            'for' => 'admin.package.edit',
            'id' => $package->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建套餐成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.package.edit")
     */
    public function editAction($id)
    {
        $packageService = new PackageService();

        $package = $packageService->getPackage($id);
        $xmCourses = $packageService->getXmCourses($id);

        $this->view->setVar('package', $package);
        $this->view->setVar('xm_courses', $xmCourses);
    }

    /**
     * @Post("/{id}/update", name="admin.package.update")
     */
    public function updateAction($id)
    {
        $packageService = new PackageService();

        $packageService->updatePackage($id);

        $content = ['msg' => '更新套餐成功'];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.package.delete")
     */
    public function deleteAction($id)
    {
        $packageService = new PackageService();

        $packageService->deletePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除套餐成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.package.restore")
     */
    public function restoreAction($id)
    {
        $packageService = new PackageService();

        $packageService->restorePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原套餐成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
