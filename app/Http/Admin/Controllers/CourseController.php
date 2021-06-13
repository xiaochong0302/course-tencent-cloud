<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Course as CourseService;
use App\Models\Category as CategoryModel;

/**
 * @RoutePrefix("/admin/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/category", name="admin.course.category")
     */
    public function categoryAction()
    {
        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['type' => CategoryModel::TYPE_COURSE]
        );

        $this->response->redirect($location);
    }

    /**
     * @Get("/search", name="admin.course.search")
     */
    public function searchAction()
    {
        $courseService = new CourseService();

        $xmCategories = $courseService->getXmCategories(0);
        $xmTeachers = $courseService->getXmTeachers(0);
        $modelTypes = $courseService->getModelTypes();
        $levelTypes = $courseService->getLevelTypes();

        $this->view->setVar('xm_categories', $xmCategories);
        $this->view->setVar('xm_teachers', $xmTeachers);
        $this->view->setVar('model_types', $modelTypes);
        $this->view->setVar('level_types', $levelTypes);
    }

    /**
     * @Get("/list", name="admin.course.list")
     */
    public function listAction()
    {
        $courseService = new CourseService();

        $pager = $courseService->getCourses();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.course.add")
     */
    public function addAction()
    {
        $courseService = new CourseService();

        $modelTypes = $courseService->getModelTypes();

        $this->view->setVar('model_types', $modelTypes);
    }

    /**
     * @Post("/create", name="admin.course.create")
     */
    public function createAction()
    {
        $courseService = new CourseService();

        $course = $courseService->createCourse();

        $location = $this->url->get([
            'for' => 'admin.course.edit',
            'id' => $course->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.course.edit")
     */
    public function editAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $xmTeachers = $courseService->getXmTeachers($id);
        $xmCategories = $courseService->getXmCategories($id);
        $xmCourses = $courseService->getXmCourses($id);
        $studyExpiryOptions = $courseService->getStudyExpiryOptions();
        $refundExpiryOptions = $courseService->getRefundExpiryOptions();

        $this->view->setVar('course', $course);
        $this->view->setVar('xm_teachers', $xmTeachers);
        $this->view->setVar('xm_categories', $xmCategories);
        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('study_expiry_options', $studyExpiryOptions);
        $this->view->setVar('refund_expiry_options', $refundExpiryOptions);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.course.update")
     */
    public function updateAction($id)
    {
        $courseService = new CourseService();

        $courseService->updateCourse($id);

        $content = ['msg' => '更新课程成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.course.delete")
     */
    public function deleteAction($id)
    {
        $courseService = new CourseService();

        $courseService->deleteCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.course.restore")
     */
    public function restoreAction($id)
    {
        $courseService = new CourseService();

        $courseService->restoreCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原课程成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="admin.course.chapters")
     */
    public function chaptersAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $chapters = $courseService->getChapters($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
    }

}
