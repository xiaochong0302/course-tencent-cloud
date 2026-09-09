<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Course as CourseService;
use App\Http\Admin\Services\CourseLearning as CourseLearningService;
use App\Http\Admin\Services\CourseUser as CourseUserService;
use App\Http\Admin\Services\User as UserService;
use App\Models\Category as CategoryModel;
use App\Traits\Service as ServiceTrait;

/**
 * @RoutePrefix("/admin/course")
 */
class CourseController extends Controller
{

    use ServiceTrait;

    /**
     * @Get("/category", name="admin.course.category")
     */
    public function categoryAction()
    {
        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['type' => CategoryModel::TYPE_COURSE]
        );

        return $this->response->redirect($location);
    }

    /**
     * @Get("/search", name="admin.course.search")
     */
    public function searchAction()
    {
        $courseService = new CourseService();

        $categoryOptions = $courseService->getCategoryOptions();
        $teacherOptions = $courseService->getTeacherOptions();
        $modelTypes = $courseService->getModelTypes();
        $levelTypes = $courseService->getLevelTypes();

        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('teacher_options', $teacherOptions);
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
        $xmCourses = $courseService->getXmCourses($id);
        $levelTypes = $courseService->getLevelTypes();
        $categoryOptions = $courseService->getCategoryOptions();
        $teacherOptions = $courseService->getTeacherOptions();
        $studyExpiryOptions = $courseService->getStudyExpiryOptions();
        $refundExpiryOptions = $courseService->getRefundExpiryOptions();

        $cos = $this->getSettings('cos');

        $this->view->setVar('cos', $cos);
        $this->view->setVar('course', $course);
        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('level_types', $levelTypes);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('teacher_options', $teacherOptions);
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

    /**
     * @Get("/{id:[0-9]+}/learnings", name="admin.course.learnings")
     */
    public function learningsAction($id)
    {
        $service = new CourseLearningService();

        $pager = $service->getLearnings($id);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/users", name="admin.course.users")
     */
    public function usersAction($id)
    {
        $service = new CourseService();
        $course = $service->getCourse($id);

        $service = new CourseUserService();
        $pager = $service->getUsers($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/user/search", name="admin.course.search_user")
     */
    public function searchUserAction($id)
    {
        $service = new CourseService();
        $course = $service->getCourse($id);

        $service = new CourseUserService();
        $sourceTypes = $service->getSourceTypes();

        $this->view->pick('course/search_user');
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id:[0-9]+}/user/add", name="admin.course.add_user")
     */
    public function addUserAction($id)
    {
        $service = new CourseService();
        $course = $service->getCourse($id);

        $this->view->pick('course/add_user');
        $this->view->setVar('course', $course);
    }

    /**
     * @Post("/{id:[0-9]+}/user/create", name="admin.course.create_user")
     */
    public function createUserAction($id)
    {
        $service = new CourseUserService();

        $service->create();

        $location = $this->url->get([
            'for' => 'admin.course.users',
            'id' => $id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '添加学员成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/user/edit/{id:[0-9]+}", name="admin.course.edit_user")
     */
    public function editUserAction($id)
    {
        $service = new CourseUserService();
        $courseUser = $service->get($id);

        $service = new CourseService();
        $course = $service->getCourse($courseUser->course_id);

        $service = new UserService();
        $user = $service->getUser($courseUser->user_id);

        $this->view->pick('course/edit_user');
        $this->view->setVar('course_user', $courseUser);
        $this->view->setVar('course', $course);
        $this->view->setVar('user', $user);
    }

    /**
     * @Post("/user/update/{id:[0-9]+}", name="admin.course.update_user")
     */
    public function updateUserAction($id)
    {
        $service = new CourseUserService();

        $service->update($id);

        $content = ['msg' => '更新学员成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/user/delete/{id:[0-9]+}", name="admin.course.delete_user")
     */
    public function deleteUserAction($id)
    {
        $service = new CourseUserService();

        $service->delete($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除学员成功',
        ];

        return $this->jsonSuccess($content);
    }

}
