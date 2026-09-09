<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Teacher\CourseList as CourseListService;
use App\Services\Logic\Teacher\TeacherInfo as TeacherInfoService;
use App\Services\Logic\Teacher\TeacherList as TeacherListService;

/**
 * @RoutePrefix("/api/teacher")
 */
class TeacherController extends Controller
{

    /**
     * @Get("/list", name="api.teacher.list")
     */
    public function listAction()
    {
        $service = new TeacherListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.teacher.info")
     */
    public function infoAction($id)
    {
        $service = new TeacherInfoService();

        $teacher = $service->handle($id);

        return $this->jsonSuccess(['teacher' => $teacher]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="api.teacher.courses")
     */
    public function coursesAction($id)
    {
        $service = new CourseListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

}
