<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $pageService = new PageService();

        $pager = $pageService->getPages();

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
        $pageService = new PageService();

        $pageService->createPage();

        $location = $this->url->get(['for' => 'admin.page.list']);

        $content = [
            'location' => $location,
            'msg' => '创建单页成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.page.edit")
     */
    public function editAction($id)
    {
        $pageService = new PageService();

        $page = $pageService->getPage($id);

        $this->view->setVar('page', $page);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.page.update")
     */
    public function updateAction($id)
    {
        $pageService = new PageService();

        $pageService->updatePage($id);

        $location = $this->url->get(['for' => 'admin.page.list']);

        $content = [
            'location' => $location,
            'msg' => '更新单页成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.page.delete")
     */
    public function deleteAction($id)
    {
        $pageService = new PageService();

        $pageService->deletePage($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除单页成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.page.restore")
     */
    public function restoreAction($id)
    {
        $pageService = new PageService();

        $pageService->restorePage($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原单页成功',
        ];

        return $this->jsonSuccess($content);
    }

}
