<?php

namespace App\Http\Web\Controllers;

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

    }

    /**
     * @Get("/{id:[0-9]+}/manage", name="web.im_group.manage")
     */
    public function manageAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/users", name="web.im_group.users")
     */
    public function usersAction()
    {

    }

    /**
     * @Post("/user/delete", name="web.im_group.delete_user")
     */
    public function deleteUserAction()
    {

    }

    /**
     * @Post("/user/block", name="web.im_group.block_user")
     */
    public function blockUserAction()
    {

    }

    /**
     * @Post("/user/unblock", name="web.im_group.unblock_user")
     */
    public function unblockUserAction()
    {

    }

}
