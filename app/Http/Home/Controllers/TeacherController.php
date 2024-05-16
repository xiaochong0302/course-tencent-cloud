<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Teacher\CourseList as TeacherCourseListService;
use App\Services\Logic\Teacher\TeacherList as TeacherListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/teacher")
 */
class TeacherController extends Controller
{

    /**
     * @Get("/list", name="home.teacher.list")
     */
    public function listAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getTeacherListUrl();
            return $this->response->redirect($location);
        }

        $this->seo->prependTitle('师资');
    }

    /**
     * @Get("/pager", name="home.teacher.pager")
     */
    public function pagerAction()
    {
        $service = new TeacherListService();

        $pager = $service->handle();

        $pager->target = 'teacher-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.teacher.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getTeacherIndexUrl($id);
            return $this->response->redirect($location);
        }

        $service = new UserInfoService();

        $user = $service->handle($id);

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        $this->seo->prependTitle(['讲师', $user['name']]);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.teacher.courses")
     */
    public function coursesAction($id)
    {
        $model = $this->request->getQuery('model', 'trim', 'vod');

        $service = new TeacherCourseListService();

        $pager = $service->handle($id);

        $pager->target = "tab-{$model}";

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
