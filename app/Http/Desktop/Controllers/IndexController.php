<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\Index as IndexService;

class IndexController extends Controller
{

    /**
     * @Get("/", name="desktop.index")
     */
    public function indexAction()
    {
        $this->seo->setKeywords($this->siteInfo['keywords']);
        $this->seo->setDescription($this->siteInfo['description']);

        $indexService = new IndexService();

        $this->view->setVar('lives', $indexService->getLives());
        $this->view->setVar('carousels', $indexService->getCarousels());
        $this->view->setVar('new_courses', $indexService->getNewCourses());
        $this->view->setVar('free_courses', $indexService->getFreeCourses());
        $this->view->setVar('vip_courses', $indexService->getVipCourses());
    }

}
