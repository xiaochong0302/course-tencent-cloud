<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Student as StudentService;

/**
 * @RoutePrefix("/admin/student")
 */
class StudentController extends Controller
{

    /**
     * @Get("/search", name="admin.student.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.student.list")
     */
    public function listAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $studentService = new StudentService();

        $pager = $studentService->getPlans();

        $course = null;

        if ($courseId > 0) {
            $course = $studentService->getCourse($courseId);
        }

        $this->view->setVar('pager', $pager);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/add", name="admin.student.add")
     */
    public function addAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $studentService = new StudentService();

        $course = null;

        if ($courseId > 0) {
            $course = $studentService->getCourse($courseId);
        }

        $this->view->setVar('course', $course);
    }

    /**
     * @Post("/create", name="admin.student.create")
     */
    public function createAction()
    {
        $studentService = new StudentService();

        $student = $studentService->createPlan();

        $location = $this->url->get(
            ['for' => 'admin.student.list'],
            ['course_id' => $student->course_id]
        );

        $content = [
            'location' => $location,
            'msg' => '添加学员成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/edit", name="admin.student.edit")
     */
    public function editAction()
    {
        $planId = $this->request->getQuery('plan_id');

        $studentService = new StudentService();

        $plan = $studentService->getPlan($planId);
        $course = $studentService->getCourse($plan->course_id);
        $student = $studentService->getStudent($plan->user_id);

        $this->view->setVar('plan', $plan);
        $this->view->setVar('course', $course);
        $this->view->setVar('student', $student);
    }

    /**
     * @Post("/update", name="admin.student.update")
     */
    public function updateAction()
    {
        $studentService = new StudentService();

        $studentService->updatePlan();

        $location = $this->url->get(['for' => 'admin.student.list']);

        $content = [
            'location' => $location,
            'msg' => '更新学员成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/learning", name="admin.student.learning")
     */
    public function learningAction()
    {
        $studentService = new StudentService();

        $pager = $studentService->getLearnings();

        $this->view->setVar('pager', $pager);
    }

}
