<?php


namespace App\Http\Web\Controllers;

use App\Services\Frontend\Teaching\ConsultList as ConsultListService;
use App\Services\Frontend\Teaching\CourseList as CourseListService;
use App\Services\Frontend\Teaching\LiveList as LiveListService;
use App\Services\Frontend\Teaching\LivePushUrl as LivePushUrlService;


/**
 * @RoutePrefix("/teaching")
 */
class TeachingController extends Controller
{

    /**
     * @Get("/", name="web.teaching.index")
     */
    public function indexAction()
    {
        $this->dispatcher->forward(['action' => 'courses']);
    }

    /**
     * @Get("/courses", name="web.teaching.courses")
     */
    public function coursesAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/lives", name="web.teaching.lives")
     */
    public function livesAction()
    {
        $service = new LiveListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="web.teaching.consults")
     */
    public function consultsAction()
    {
        $service = new ConsultListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/live/push", name="web.teaching.live_push")
     */
    public function livePushAction()
    {
        $service = new LivePushUrlService();

        $pushUrl = $service->handle();

        $qrcode = $this->url->get(
            ['for' => 'web.qrcode'],
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