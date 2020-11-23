<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Vip\CourseList as VipCourseListService;
use App\Services\Logic\Vip\UserList as VipUserListService;

/**
 * @RoutePrefix("/api/vip")
 */
class VipController extends Controller
{

    /**
     * @Get("/courses", name="api.vip.courses")
     */
    public function coursesAction()
    {
        $type = $this->request->getQuery('type', 'string', 'discount');

        $service = new VipCourseListService();

        $pager = $service->handle($type);

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/users", name="api.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

}
