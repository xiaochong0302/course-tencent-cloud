<?php


namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Teaching\ConsultList as ConsultListService;
use App\Services\Frontend\Teaching\CourseList as CourseListService;
use App\Services\Frontend\Teaching\LiveList as LiveListService;
use App\Services\Frontend\Teaching\LivePushUrl as LivePushUrlService;
use Phalcon\Mvc\Dispatcher;


/**
 * @RoutePrefix("/teaching")
 */
class TeachingController extends Controller
{

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'desktop.account.login']);
            return false;
        }

        return true;
    }

    /**
     * @Get("/", name="desktop.teaching.index")
     */
    public function indexAction()
    {
        $this->dispatcher->forward(['action' => 'courses']);
    }

    /**
     * @Get("/courses", name="desktop.teaching.courses")
     */
    public function coursesAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/lives", name="desktop.teaching.lives")
     */
    public function livesAction()
    {
        $service = new LiveListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="desktop.teaching.consults")
     */
    public function consultsAction()
    {
        $service = new ConsultListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/live/push", name="desktop.teaching.live_push")
     */
    public function livePushAction()
    {
        $service = new LivePushUrlService();

        $pushUrl = $service->handle();

        $qrcode = $this->url->get(
            ['for' => 'desktop.qrcode'],
            ['text' => urlencode($pushUrl)]
        );

        $pos = strrpos($pushUrl, '/');

        $obs = [
            'fms_url' => substr($pushUrl, 0, $pos + 1),
            'stream_code' => substr($pushUrl, $pos + 1),
        ];

        $this->view->pick('teaching/live_push');
        $this->view->setVar('qrcode', $qrcode);
        $this->view->setVar('obs', $obs);
    }

}