<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\User\CourseList as UserCourseListService;
use App\Services\Logic\User\FavoriteList as UserFavoriteListService;
use App\Services\Logic\User\FriendList as UserFriendListService;
use App\Services\Logic\User\GroupList as UserGroupListService;
use App\Services\Logic\User\UserInfo as UserInfoService;

/**
 * @RoutePrefix("/api/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.user.info")
     */
    public function infoAction($id)
    {
        $service = new UserInfoService();

        $user = $service->handle($id);

        return $this->jsonSuccess(['user' => $user]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="api.user.courses")
     */
    public function coursesAction($id)
    {
        $service = new UserCourseListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/favorites", name="api.user.favorites")
     */
    public function favoritesAction($id)
    {
        $service = new UserFavoriteListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/friends", name="api.user.friends")
     */
    public function friendsAction($id)
    {
        $service = new UserFriendListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/groups", name="api.user.groups")
     */
    public function groupsAction($id)
    {
        $service = new UserGroupListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
    }

}
