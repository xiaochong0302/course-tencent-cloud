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
     * @Get("/list", name="home.group.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('群组');

        $this->view->pick('im/group/list');
    }

    /**
     * @Get("/pager", name="home.group.pager")
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
     * @Get("/{id:[0-9]+}", name="home.group.show")
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
     * @Get("/{id:[0-9]+}/users", name="home.group.users")
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
     * @Get("/{id:[0-9]+}/users/active", name="home.group.active_users")
     */
    public function activeUsersAction($id)
    {
        $service = new ImGroupService();

        $users = $service->getActiveGroupUsers($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im/group/active_users');
        $this->view->setVar('users', $users);
    }

}
