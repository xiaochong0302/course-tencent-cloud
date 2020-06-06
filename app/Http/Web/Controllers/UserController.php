<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\User\CourseList as UserCourseListService;
use App\Services\Frontend\User\FavoriteList as UserFavoriteListService;
use App\Services\Frontend\User\UserInfo as UserInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.user.show")
     */
    public function showAction($id)
    {
        $service = new UserInfoService();

        $user = $service->handle($id);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="web.user.courses")
     */
    public function coursesAction($id)
    {
        $target = $this->request->get('target', 'trim', 'tab-courses');

        $service = new UserCourseListService();

        $pager = $service->handle($id);
        $pager->items = kg_array_object($pager->items);
        $pager->target = $target;

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/favorites", name="web.user.favorites")
     */
    public function favoritesAction($id)
    {
        $target = $this->request->get('target', 'trim', 'tab-favorites');

        $service = new UserFavoriteListService();

        $pager = $service->handle($id);
        $pager->items = kg_array_object($pager->items);
        $pager->target = $target;

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/friends", name="web.user.friends")
     */
    public function friendsAction($id)
    {
        $target = $this->request->get('target', 'trim', 'tab-friends');

        $service = new UserFavoriteListService();

        $pager = $service->handle($id);
        $pager->items = kg_array_object($pager->items);
        $pager->target = $target;

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
