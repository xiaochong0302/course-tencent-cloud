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

        $template = $this->siteInfo['index_tpl'] ?? 'full';

        if ($template == 'full') {
            $this->fullIndex();
        } else {
            $this->simpleIndex();
        }
    }

    protected function fullIndex()
    {
        $service = new IndexService();

        $this->view->pick('index/full');
        $this->view->setVar('lives', $service->getLives());
        $this->view->setVar('carousels', $service->getCarousels());
        $this->view->setVar('new_courses', $service->getNewCourses());
        $this->view->setVar('free_courses', $service->getFreeCourses());
        $this->view->setVar('vip_courses', $service->getVipCourses());
    }

    protected function simpleIndex()
    {
        $service = new IndexService();

        $this->view->pick('index/simple');
        $this->view->setVar('lives', $service->getLives());
        $this->view->setVar('carousels', $service->getCarousels());
        $this->view->setVar('new_courses', $service->getSimpleNewCourses());
        $this->view->setVar('free_courses', $service->getSimpleFreeCourses());
        $this->view->setVar('vip_courses', $service->getSimpleVipCourses());
    }

}
