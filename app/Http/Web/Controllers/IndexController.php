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

        $this->view->setVar('slide_list', $indexService->getSlideList());
        $this->view->setVar('live_list', $indexService->getLiveList());
        $this->view->setVar('new_course_list', $indexService->getNewCourseList());
        $this->view->setVar('free_course_list', $indexService->getFreeCourseList());
        $this->view->setVar('vip_course_list', $indexService->getVipCourseList());
    }

}
