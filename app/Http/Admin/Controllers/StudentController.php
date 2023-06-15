<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $studentService = new StudentService();

        $sourceTypes = $studentService->getSourceTypes();

        $xmCourses = $studentService->getXmCourses('all', $courseId);

        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('xm_courses', $xmCourses);
    }

    /**
     * @Get("/list", name="admin.student.list")
     */
    public function listAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int', 0);

        $studentService = new StudentService();

        $pager = $studentService->getRelations();

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

        $xmCourses = $studentService->getXmCourses('all', $courseId);

        $this->view->setVar('xm_courses', $xmCourses);
    }

    /**
     * @Post("/create", name="admin.student.create")
     */
    public function createAction()
    {
        $studentService = new StudentService();

        $student = $studentService->createRelation();

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
        $relationId = $this->request->getQuery('relation_id', 'int');

        $studentService = new StudentService();

        $relation = $studentService->getRelation($relationId);
        $course = $studentService->getCourse($relation->course_id);
        $student = $studentService->getStudent($relation->user_id);

        $this->view->setVar('relation', $relation);
        $this->view->setVar('course', $course);
        $this->view->setVar('student', $student);
    }

    /**
     * @Post("/update", name="admin.student.update")
     */
    public function updateAction()
    {
        $studentService = new StudentService();

        $studentService->updateRelation();

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
