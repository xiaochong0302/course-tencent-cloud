<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\ImGroup as ImGroupService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im/group")
 */
class ImGroupController extends Controller
{

    /**
     * @Get("/list", name="web.im_group.list")
     */
    public function listAction()
    {
        $this->siteSeo->prependTitle('群组');
    }

    /**
     * @Get("/pager", name="web.im_group.pager")
     */
    public function pagerAction()
    {
        $service = new ImGroupService();

        $pager = $service->getGroups();
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'group-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im_group/pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.im_group.show")
     */
    public function showAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->view->setVar('group', $group);
    }

    /**
     * @Get("/{id:[0-9]+}/users", name="web.im_group.users")
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
     * @Get("/{id:[0-9]+}/edit", name="web.im_group.edit")
     */
    public function editAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->view->setVar('group', $group);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.im_group.update")
     */
    public function updateAction($id)
    {
        $service = new ImGroupService();

        $service->updateGroup($id);

        $content = ['msg' => '更新群组成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{gid:[0-9]+}/user/{uid:[0-9]+}/delete", name="web.im_group.delete_user")
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
