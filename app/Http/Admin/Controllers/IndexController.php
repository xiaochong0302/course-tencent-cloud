<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AuthMenu as AuthMenuService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin")
 */
class IndexController extends Controller
{

    /**
     * @Get("/", name="admin.index")
     */
    public function indexAction()
    {
        $authMenu = new AuthMenuService();

        $topMenus = $authMenu->getTopMenus();
        $leftMenus = $authMenu->getLeftMenus();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('top_menus', $topMenus);
        $this->view->setVar('left_menus', $leftMenus);
    }

    /**
     * @Get("/main", name="admin.main")
     */
    public function mainAction()
    {
        /*
         $service = new \App\Services\Order();
         $course = \App\Models\Course::findFirstById(1152);
         $service->createCourseOrder($course);
        */

        /*
         $service = new \App\Services\Order();
         $package = \App\Models\Package::findFirstById(5);
         $service->createPackageOrder($package);
         */

        $refund = new \App\Services\Refund();
        $order = \App\Models\Order::findFirstById(131);
        $amount = $refund->getRefundAmount($order);

        dd($amount);

    }

}
