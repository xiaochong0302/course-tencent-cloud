<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\XmCourse as XmCourseService;

/**
 * @RoutePrefix("/admin/xm/course")
 */
class XmCourseController extends Controller
{

    /**
     * @Get("/all", name="admin.xm.course.all")
     */
    public function allAction()
    {
        $xmCourseService = new XmCourseService();

        $pager = $xmCourseService->getAllCourses();

        return $this->ajaxSuccess([
            'count' => $pager->total_items,
            'data' => $pager->items,
        ]);
    }

    /**
     * @Get("/paid", name="admin.xm.course.paid")
     */
    public function paidAction()
    {
        $xmCourseService = new XmCourseService();

        $pager = $xmCourseService->getPaidCourses();

        return $this->ajaxSuccess([
            'count' => $pager->total_items,
            'data' => $pager->items,
        ]);
    }

}
