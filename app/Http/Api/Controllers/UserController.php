<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\User\StudyCourseList as StudyCourseListService;
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

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        return $this->jsonSuccess(['user' => $user]);
    }

    /**
     * @Get("/{id:[0-9]+}/study-courses", name="api.user.study_courses")
     */
    public function studyCoursesAction($id)
    {
        $service = new StudyCourseListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

}
