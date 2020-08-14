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
        $this->seo->setKeywords($this->site['keywords']);
        $this->seo->setDescription($this->site['description']);

        $indexService = new IndexService();

        $this->view->setVar('lives', $indexService->getLives());
        $this->view->setVar('carousels', $indexService->getCarousels());
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
