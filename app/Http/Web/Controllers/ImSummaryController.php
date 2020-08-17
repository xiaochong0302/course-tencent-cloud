<?php

namespace App\Http\Web\Controllers;

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

}
