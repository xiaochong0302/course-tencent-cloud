<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/users", name="api.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

}
