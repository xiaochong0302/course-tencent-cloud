<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\ImGroup as ImGroupService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im")
 */
class ImSummaryController extends Controller
{

    /**
     * @Get("/active/groups", name="web.im.active_groups")
     */
    public function activeGroupsAction()
    {
        $this->seo->prependTitle('群组');
    }

    /**
     * @Get("/active/users", name="web.im.active_users")
     */
    public function activeUsersAction()
    {
        $this->seo->prependTitle('群组');
    }

    /**
     * @Get("/online/users", name="web.im.online_users")
     */
    public function onlineUsersAction()
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
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'group-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im_group/pager');
        $this->view->setVar('pager', $pager);
    }

}
