<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ImGroup as ImGroupService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im/group")
 */
class ImGroupController extends Controller
{

    /**
     * @Get("/list", name="home.im_group.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('群组');

        $this->view->pick('im/group/list');
    }

    /**
     * @Get("/pager", name="home.im_group.pager")
     */
    public function pagerAction()
    {
        $service = new ImGroupService();

        $pager = $service->getGroups();

        $pager->target = 'group-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im/group/pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.im_group.show")
     */
    public function showAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->seo->prependTitle([$group['name'], '群组']);

        $this->view->pick('im/group/show');
        $this->view->setVar('group', $group);
    }

    /**
     * @Get("/{id:[0-9]+}/users", name="home.im_group.users")
     */
    public function usersAction($id)
    {
        $service = new ImGroupService();

        $pager = $service->getGroupUsers($id);

        $pager->target = 'user-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im/group/users');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.im_group.edit")
     */
    public function editAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);

        $this->view->pick('im/group/edit');
        $this->view->setVar('group', $group);
    }

    /**
     * @Get("/{id:[0-9]+}/users/active", name="home.im_group.active_users")
     */
    public function activeUsersAction($id)
    {
        $service = new ImGroupService();

        $users = $service->getActiveGroupUsers($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im/group/active_users');
        $this->view->setVar('users', $users);
    }

    /**
     * @Get("/{id:[0-9]+}/users/manage", name="home.im_group.manage_users")
     */
    public function manageUsersAction($id)
    {
        $service = new ImGroupService();

        $group = $service->getGroup($id);
        $pager = $service->getGroupUsers($id);

        $this->view->pick('im/group/manage_users');
        $this->view->setVar('group', $group);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.im_group.update")
     */
    public function updateAction($id)
    {
        $service = new ImGroupService();

        $service->updateGroup($id);

        return $this->jsonSuccess(['msg' => '更新群组成功']);
    }

}
