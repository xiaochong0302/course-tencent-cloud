<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Nav as NavService;

/**
 * @RoutePrefix("/admin/nav")
 */
class NavController extends Controller
{

    /**
     * @Get("/list", name="admin.nav.list")
     */
    public function listAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $navService = new NavService();

        $parent = $navService->getParentNav($parentId);
        $navs = $navService->getChildNavs($parentId);

        $this->view->setVar('parent', $parent);
        $this->view->setVar('navs', $navs);
    }

    /**
     * @Get("/add", name="admin.nav.add")
     */
    public function addAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $navService = new NavService();

        $topNavs = $navService->getTopNavs();

        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('top_navs', $topNavs);
    }

    /**
     * @Post("/create", name="admin.nav.create")
     */
    public function createAction()
    {
        $navService = new NavService();

        $nav = $navService->createNav();

        $location = $this->url->get(
            ['for' => 'admin.nav.list'],
            ['parent_id' => $nav->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '创建导航成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.nav.edit")
     */
    public function editAction($id)
    {
        $navService = new NavService();

        $nav = $navService->getNav($id);

        $this->view->setVar('nav', $nav);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.nav.update")
     */
    public function updateAction($id)
    {
        $navService = new NavService();

        $nav = $navService->getNav($id);

        $navService->updateNav($id);

        $location = $this->url->get(
            ['for' => 'admin.nav.list'],
            ['parent_id' => $nav->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '更新导航成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.nav.delete")
     */
    public function deleteAction($id)
    {
        $navService = new NavService();

        $navService->deleteNav($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除导航成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.nav.restore")
     */
    public function restoreAction($id)
    {
        $navService = new NavService();

        $navService->restoreNav($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原导航成功',
        ];

        return $this->jsonSuccess($content);
    }

}
