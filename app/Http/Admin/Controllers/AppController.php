<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\App as AppService;

/**
 * @RoutePrefix("/admin/app")
 */
class AppController extends Controller
{

    /**
     * @Get("/list", name="admin.app.list")
     */
    public function listAction()
    {
        $appService = new AppService();

        $pager = $appService->getApps();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.app.add")
     */
    public function addAction()
    {
        $appService = new AppService();

        $types = $appService->getAppTypes();

        $this->view->setVar('types', $types);
    }

    /**
     * @Post("/create", name="admin.app.create")
     */
    public function createAction()
    {
        $appService = new AppService();

        $appService->createApp();

        $location = $this->url->get(['for' => 'admin.app.list']);

        $content = [
            'location' => $location,
            'msg' => '创建应用成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.app.edit")
     */
    public function editAction($id)
    {
        $appService = new AppService;

        $app = $appService->getApp($id);
        $types = $appService->getAppTypes();

        $this->view->setVar('app', $app);
        $this->view->setVar('types', $types);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.app.update")
     */
    public function updateAction($id)
    {
        $appService = new AppService();

        $appService->updateApp($id);

        $location = $this->url->get(['for' => 'admin.app.list']);

        $content = [
            'location' => $location,
            'msg' => '更新应用成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.app.delete")
     */
    public function deleteAction($id)
    {
        $appService = new AppService();

        $appService->deleteApp($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除应用成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.app.restore")
     */
    public function restoreAction($id)
    {
        $appService = new AppService();

        $appService->restoreApp($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原应用成功',
        ];

        return $this->jsonSuccess($content);
    }

}
