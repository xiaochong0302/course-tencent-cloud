<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Index as IndexService;

class IndexController extends Controller
{

    /**
     * @Get("/", name="web.index")
     */
    public function indexAction()
    {
        $this->siteSeo->setKeywords($this->siteSettings['keywords']);
        $this->siteSeo->setDescription($this->siteSettings['description']);

        $indexService = new IndexService();

        $this->view->setVar('carousels', $indexService->getCarousels());
        $this->view->setVar('lives', $indexService->getLives());
        $this->view->setVar('new_courses', $indexService->getNewCourses());
        $this->view->setVar('free_courses', $indexService->getFreeCourses());
        $this->view->setVar('vip_courses', $indexService->getVipCourses());
    }

    /**
     * @Get("/im", name="web.im")
     */
    public function imAction()
    {

    }

}
