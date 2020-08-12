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
        $this->seo->prependTitle('群组');
    }

    /**
     * @Get("/pager", name="web.im_group.pager")
     */
    public function pagerAction()
    {
        $service = new ImGroupService();

        $pager = $service->getGroups();

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

        $pager = $service->getGroupUsers($id);

        $pager->target = 'user-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/users/active", name="web.im_group.active_users")
     */
    public function activeUsersAction($id)
    {
        $service = new ImGroupService();

        $users = $service->getActiveGroupUsers($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im_group/active_users');
        $this->view->setVar('users', $users);
    }

}
