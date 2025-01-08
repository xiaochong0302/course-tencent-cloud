<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Teacher\Console\ConsultList as ConsultListService;
use App\Services\Logic\Teacher\Console\CourseList as CourseListService;
use App\Services\Logic\Teacher\Console\LiveList as LiveListService;
use App\Services\Logic\Teacher\Console\LivePushUrl as LivePushUrlService;
use Phalcon\Mvc\Dispatcher;

/**
 * @RoutePrefix("/tc")
 */
class TeacherConsoleController extends Controller
{

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        if ($this->authUser->id == 0) {
            $dispatcher->forward([
                'controller' => 'account',
                'action' => 'login',
            ]);
            return false;
        }

        return true;
    }

    public function initialize()
    {
        parent::initialize();

        $authUser = $this->getAuthUser(false);

        $this->seo->prependTitle('教学中心');

        $this->view->setVar('auth_user', $authUser);
    }

    /**
     * @Get("/", name="home.tc.index")
     */
    public function indexAction()
    {
        $this->dispatcher->forward(['action' => 'courses']);
    }

    /**
     * @Get("/courses", name="home.tc.courses")
     */
    public function coursesAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $this->view->pick('teacher/console/courses');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/lives", name="home.tc.lives")
     */
    public function livesAction()
    {
        $service = new LiveListService();

        $pager = $service->handle();

        $this->view->pick('teacher/console/lives');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="home.tc.consults")
     */
    public function consultsAction()
    {
        $service = new ConsultListService();

        $pager = $service->handle();

        $this->view->pick('teacher/console/consults');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/live/{id:[0-9]+}", name="home.tc.live")
     */
    public function liveAction($id)
    {
        $service = new LivePushUrlService();

        $pushUrl = $service->handle($id);

        $qrcode = $this->url->get(
            ['for' => 'home.qrcode'],
            ['text' => urlencode($pushUrl)]
        );

        $pos = strrpos($pushUrl, '/');

        $obs = [
            'fms_url' => substr($pushUrl, 0, $pos + 1),
            'stream_code' => substr($pushUrl, $pos + 1),
        ];

        $this->view->pick('teacher/console/live');
        $this->view->setVar('qrcode', $qrcode);
        $this->view->setVar('obs', $obs);
    }

}
