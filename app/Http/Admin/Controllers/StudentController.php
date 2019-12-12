<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\CourseStudent as CourseStudentService;

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
        $courseId = $this->request->getQuery('course_id', 'int', '');

        $service = new CourseStudentService();

        $pager = $service->getCourseStudents();

        $this->view->setVar('pager', $pager);
        $this->view->setVar('course_id', $courseId);
    }

    /**
     * @Get("/add", name="admin.student.add")
     */
    public function addAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int', '');

        $this->view->setVar('course_id', $courseId);
    }

    /**
     * @Post("/create", name="admin.student.create")
     */
    public function createAction()
    {
        $service = new CourseStudentService();

        $student = $service->createCourseStudent();

        $location = $this->url->get(
            ['for' => 'admin.student.list'],
            ['course_id' => $student->course_id]
        );

        $content = [
            'location' => $location,
            'msg' => '添加学员成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/edit", name="admin.student.edit")
     */
    public function editAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int');
        $userId = $this->request->getQuery('user_id', 'int');

        $service = new CourseStudentService();

        $courseStudent = $service->getCourseStudent($courseId, $userId);
        $course = $service->getCourse($courseId);
        $student = $service->getStudent($userId);

        $this->view->setVar('course_student', $courseStudent);
        $this->view->setVar('course', $course);
        $this->view->setVar('student', $student);
    }

    /**
     * @Post("/update", name="admin.student.update")
     */
    public function updateAction()
    {
        $service = new CourseStudentService();

        $student = $service->updateCourseStudent();

        $location = $this->url->get(
            ['for' => 'admin.student.list'],
            ['course_id' => $student->course_id]
        );

        $content = [
            'location' => $location,
            'msg' => '更新学员成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/learning", name="admin.student.learning")
     */
    public function learningAction()
    {
        $service = new CourseStudentService();

        $pager = $service->getCourseLearnings();

        $this->view->setVar('pager', $pager);
    }

}
