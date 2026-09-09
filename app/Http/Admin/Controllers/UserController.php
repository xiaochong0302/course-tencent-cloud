<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\User as UserService;
use App\Http\Admin\Services\UserCourseAssign as UserCourseAssignService;
use App\Http\Admin\Services\UserCourseStudy as UserCourseStudyService;
use App\Http\Admin\Services\UserOnline as UserOnlineService;
use App\Http\Admin\Services\UserOrder as UserOrderService;
use App\Models\Role as RoleModel;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/search", name="admin.user.search")
     */
    public function searchAction()
    {
        $userService = new UserService();

        $eduRoleTypes = $userService->getEduRoleTypes();
        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('edu_role_types', $eduRoleTypes);
        $this->view->setVar('admin_roles', $adminRoles);
    }

    /**
     * @Get("/list", name="admin.user.list")
     */
    public function listAction()
    {
        $userService = new UserService();

        $pager = $userService->getUsers();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.user.show")
     */
    public function showAction($id)
    {
        $userService = new UserService();

        $user = $userService->getUser($id);
        $account = $userService->getAccount($id);
        $balance = $userService->getUserBalance($id);

        $this->view->setVar('user', $user);
        $this->view->setVar('account', $account);
        $this->view->setVar('balance', $balance);
    }

    /**
     * @Get("/{id:[0-9]+}/orders", name="admin.user.orders")
     */
    public function ordersAction($id)
    {
        $service = new UserOrderService();

        $pager = $service->getOrders($id);

        $pager->target = 'order-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/study-courses", name="admin.user.study_courses")
     */
    public function studyCoursesAction($id)
    {
        $service = new UserCourseStudyService();

        $pager = $service->getCourses($id);

        $pager->target = 'study-course-list';

        $this->view->pick('user/study_courses');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/onlines", name="admin.user.onlines")
     */
    public function onlinesAction($id)
    {
        $service = new UserOnlineService();

        $pager = $service->getOnlines($id);

        $pager->target = 'online-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.user.add")
     */
    public function addAction()
    {
        $userService = new UserService();

        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('admin_roles', $adminRoles);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.user.edit")
     */
    public function editAction($id)
    {
        $userService = new UserService();

        $user = $userService->getUser($id);
        $account = $userService->getAccount($id);
        $adminRoles = $userService->getAdminRoles();

        $this->view->setVar('user', $user);
        $this->view->setVar('account', $account);
        $this->view->setVar('admin_roles', $adminRoles);
    }

    /**
     * @Post("/create", name="admin.user.create")
     */
    public function createAction()
    {
        $adminRole = $this->request->getPost('admin_role', 'int', 0);

        if ($adminRole == RoleModel::ROLE_ROOT) {
            return $this->response->redirect(['action' => 'list']);
        }

        $userService = new UserService();

        $userService->createUser();

        $location = $this->url->get(['for' => 'admin.user.list']);

        $content = [
            'location' => $location,
            'msg' => '新增用户成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.user.update")
     */
    public function updateAction($id)
    {
        $adminRole = $this->request->getPost('admin_role', 'int', 0);

        if ($adminRole == RoleModel::ROLE_ROOT) {
            return $this->response->redirect(['action' => 'list']);
        }

        $type = $this->request->getPost('type', 'string', 'user');

        $userService = new UserService();

        if ($type == 'user') {
            $userService->updateUser($id);
        } else {
            $userService->updateAccount($id);
        }

        $content = ['msg' => '更新用户成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Route("/{id:[0-9]+}/course/assign", name="admin.user.assign_course")
     */
    public function assignCourseAction($id)
    {
        $assignService = new UserCourseAssignService();

        if ($this->request->isPost()) {

            $assignService->assignCourse($id);

            return $this->jsonSuccess(['msg' => '赠送课程成功']);

        } else {

            $xmCourses = $assignService->getXmCourses();

            $userService = new UserService();

            $user = $userService->getUser($id);

            $this->view->pick('user/assign_course');
            $this->view->setVar('xm_courses', $xmCourses);
            $this->view->setVar('user', $user);
        }
    }

}
