<?php

namespace App\Http\Desktop\Controllers;

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
     * @Get("/", name="desktop.vip.index")
     */
    public function indexAction()
    {
        $service = new VipOptionListService();

        $vipOptions = $service->handle();

        $this->seo->prependTitle('会员');

        $this->view->setVar('vip_options', $vipOptions);
    }

    /**
     * @Get("/courses", name="desktop.vip.courses")
     */
    public function coursesAction()
    {
        $type = $this->request->getQuery('type', 'string', 'discount');

        $service = new VipCourseListService();

        $pager = $service->handle($type);

        $pager->target = "tab-{$type}-courses";

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/users", name="desktop.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();

        $pager->target = 'tab-users';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
