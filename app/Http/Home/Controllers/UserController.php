<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\User\StudyCourseList as UserStudyCourseListService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.user.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getUserIndexUrl($id);
            return $this->response->redirect($location);
        }

        $service = new UserInfoService();

        $user = $service->handle($id);

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        $this->seo->prependTitle(['学员', $user['name']]);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/study-courses", name="home.user.study_courses")
     */
    public function studyCoursesAction($id)
    {
        $service = new UserStudyCourseListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-study-courses';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/study_courses');
        $this->view->setVar('pager', $pager);
    }

}
