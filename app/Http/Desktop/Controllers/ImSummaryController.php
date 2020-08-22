<?php

namespace App\Http\Desktop\Controllers;

/**
 * @RoutePrefix("/im")
 */
class ImSummaryController extends Controller
{

    /**
     * @Get("/active/groups", name="desktop.im.active_groups")
     */
    public function activeGroupsAction()
    {
        $this->seo->prependTitle('群组');
    }

    /**
     * @Get("/active/users", name="desktop.im.active_users")
     */
    public function activeUsersAction()
    {
        $this->seo->prependTitle('群组');
    }

    /**
     * @Get("/online/users", name="desktop.im.online_users")
     */
    public function onlineUsersAction()
    {
        $this->seo->prependTitle('群组');
    }

}
