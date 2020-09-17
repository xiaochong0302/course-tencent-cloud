<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\User\CourseList as UserCourseListService;
use App\Services\Logic\User\FavoriteList as UserFavoriteListService;
use App\Services\Logic\User\FriendList as UserFriendListService;
use App\Services\Logic\User\GroupList as UserGroupListService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.user.show")
     */
    public function showAction($id)
    {
        $service = new UserInfoService();

        $user = $service->handle($id);

        $this->seo->prependTitle([$user['name'], '个人主页']);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.user.courses")
     */
    public function coursesAction($id)
    {
        $service = new UserCourseListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-courses';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/courses');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/favorites", name="home.user.favorites")
     */
    public function favoritesAction($id)
    {
        $service = new UserFavoriteListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-favorites';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/favorites');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/friends", name="home.user.friends")
     */
    public function friendsAction($id)
    {
        $service = new UserFriendListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-friends';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/friends');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/groups", name="home.user.groups")
     */
    public function groupsAction($id)
    {
        $service = new UserGroupListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-groups';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/groups');
        $this->view->setVar('pager', $pager);
    }

}
