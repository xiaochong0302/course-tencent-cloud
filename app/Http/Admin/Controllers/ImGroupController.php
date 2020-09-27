<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\ImGroup as ImGroupService;

/**
 * @RoutePrefix("/admin/im/group")
 */
class ImGroupController extends Controller
{

    /**
     * @Get("/list", name="admin.group.list")
     */
    public function listAction()
    {
        $groupService = new ImGroupService();

        $pager = $groupService->getGroups();

        $this->view->pick('im/group/list');

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.group.search")
     */
    public function searchAction()
    {
        $this->view->pick('im/group/search');
    }

    /**
     * @Get("/add", name="admin.group.add")
     */
    public function addAction()
    {
        $this->view->pick('im/group/add');
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.group.edit")
     */
    public function editAction($id)
    {
        $groupService = new ImGroupService();

        $group = $groupService->getGroup($id);

        $this->view->pick('im/group/edit');

        $this->view->setVar('group', $group);
    }

    /**
     * @Post("/create", name="admin.group.create")
     */
    public function createAction()
    {
        $groupService = new ImGroupService();

        $group = $groupService->createGroup();

        $location = $this->url->get([
            'for' => 'admin.group.edit',
            'id' => $group->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建群组成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.group.update")
     */
    public function updateAction($id)
    {
        $groupService = new ImGroupService();

        $groupService->updateGroup($id);

        $location = $this->url->get(['for' => 'admin.group.list']);

        $content = [
            'location' => $location,
            'msg' => '更新群组成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.group.delete")
     */
    public function deleteAction($id)
    {
        $groupService = new ImGroupService();

        $groupService->deleteGroup($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除群组成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.group.restore")
     */
    public function restoreAction($id)
    {
        $groupService = new ImGroupService();

        $groupService->restoreGroup($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原群组成功',
        ];

        return $this->jsonSuccess($content);
    }

}
