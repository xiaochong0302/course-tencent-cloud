<?php


namespace App\Http\Web\Controllers;

use App\Services\Frontend\Teaching\ConsultList as TclService;
use App\Services\Frontend\Teaching\LiveList as TllService;


/**
 * @RoutePrefix("/teaching")
 */
class TeachingController extends Controller
{

    /**
     * @Get("/lives", name="web.teaching.lives")
     */
    public function livesAction()
    {
        $service = new TllService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="web.teaching.consults")
     */
    public function consultsAction()
    {
        $service = new TclService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

}