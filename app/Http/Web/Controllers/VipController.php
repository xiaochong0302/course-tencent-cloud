<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Vip\CourseList as VipCourseListService;
use App\Services\Frontend\Vip\OptionList as VipOptionListService;
use App\Services\Frontend\Vip\UserList as VipUserListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/vip")
 */
class VipController extends Controller
{

    /**
     * @Get("/", name="web.vip.index")
     */
    public function indexAction()
    {
        $service = new VipOptionListService();

        $vipOptions = $service->handle();

        $this->view->setVar('vip_options', $vipOptions);
    }

    /**
     * @Get("/courses", name="web.vip.courses")
     */
    public function coursesAction()
    {
        $service = new VipCourseListService();

        $pager = $service->handle();
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'tab-courses';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/users", name="web.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'tab-users';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
