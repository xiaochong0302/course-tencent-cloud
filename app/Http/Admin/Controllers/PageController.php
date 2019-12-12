<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Page as PageService;

/**
 * @RoutePrefix("/admin/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/list", name="admin.page.list")
     */
    public function listAction()
    {
        $service = new PageService();

        $pager = $service->getPages();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.page.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.page.create")
     */
    public function createAction()
    {
        $service = new PageService();

        $service->createPage();

        $location = $this->url->get(['for' => 'admin.page.list']);

        $content = [
            'location' => $location,
            'msg' => '创建单页成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.page.edit")
     */
    public function editAction($id)
    {
        $service = new PageService;

        $page = $service->getPage($id);

        $this->view->setVar('page', $page);
    }

    /**
     * @Post("/{id}/update", name="admin.page.update")
     */
    public function updateAction($id)
    {
        $service = new PageService();

        $service->updatePage($id);

        $location = $this->url->get(['for' => 'admin.page.list']);

        $content = [
            'location' => $location,
            'msg' => '更新单页成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.page.delete")
     */
    public function deleteAction($id)
    {
        $service = new PageService();

        $service->deletePage($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除单页成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.page.restore")
     */
    public function restoreAction($id)
    {
        $service = new PageService();

        $service->restorePage($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原单页成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
