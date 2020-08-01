<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\ImGroup as ImGroupService;

/**
 * @RoutePrefix("/igm")
 */
class ImGroupManageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/users", name="web.igm.users")
     */
    public function usersAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $pager = $service->getGroupUsers($id);

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('group', $group);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="web.igm.edit")
     */
    public function editAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->view->setVar('group', $group);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.igm.update")
     */
    public function updateAction($id)
    {
        $service = new ImGroupService();

        $service->updateGroup($id);

        $content = ['msg' => '更新群组成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{gid:[0-9]+}/user/{uid:[0-9]+}/delete", name="web.igm.delete_user")
     */
    public function deleteGroupUserAction($gid, $uid)
    {
        $service = new ImGroupService();

        $service->deleteGroupUser($gid, $uid);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '移除用户成功',
        ];

        return $this->jsonSuccess($content);
    }

}
