<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ImGroup as ImGroupService;

/**
 * @RoutePrefix("/igm")
 */
class ImGroupManageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/users", name="home.igm.users")
     */
    public function usersAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $pager = $service->getGroupUsers($id);

        $this->view->pick('im/group/manage/users');
        $this->view->setVar('group', $group);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.igm.edit")
     */
    public function editAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->view->pick('im/group/manage/edit');
        $this->view->setVar('group', $group);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.igm.update")
     */
    public function updateAction($id)
    {
        $service = new ImGroupService();

        $service->updateGroup($id);

        return $this->jsonSuccess(['msg' => '更新群组成功']);
    }

    /**
     * @Post("/{gid:[0-9]+}/user/{uid:[0-9]+}/delete", name="home.igm.delete_user")
     */
    public function deleteGroupUserAction($gid, $uid)
    {
        $service = new ImGroupService();

        $service->deleteGroupUser($gid, $uid);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '移除用户成功',
        ];

        return $this->jsonSuccess($content);
    }

}
