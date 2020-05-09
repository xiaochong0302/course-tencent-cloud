<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\User\CourseList as UserCourseListService;
use App\Services\Frontend\User\UserInfo as UserInfoService;

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
        $service = new UserCourseListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
