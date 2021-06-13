<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Package as PackageService;

/**
 * @RoutePrefix("/admin/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/search", name="admin.package.search")
     */
    public function searchAction()
    {

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

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.package.edit")
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
     * @Post("/{id:[0-9]+}/update", name="admin.package.update")
     */
    public function updateAction($id)
    {
        $packageService = new PackageService();

        $packageService->updatePackage($id);

        $content = ['msg' => '更新套餐成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.package.delete")
     */
    public function deleteAction($id)
    {
        $packageService = new PackageService();

        $packageService->deletePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.package.restore")
     */
    public function restoreAction($id)
    {
        $packageService = new PackageService();

        $packageService->restorePackage($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

}
