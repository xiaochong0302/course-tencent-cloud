<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Teacher\TeacherList as TeacherListService;

/**
 * @RoutePrefix("/teacher")
 */
class TeacherController extends Controller
{

    /**
     * @Get("/list", name="web.teacher")
     */
    public function listAction()
    {
        $service = new TeacherListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.teacher.show")
     */
    public function showAction()
    {

    }

}
