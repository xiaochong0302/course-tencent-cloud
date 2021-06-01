<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Index as IndexService;
use App\Traits\Client as ClientTrait;
use Phalcon\Mvc\Dispatcher;

class IndexController extends Controller
{

    use ClientTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isMobileBrowser() && $this->h5Enabled()) {

            $this->response->redirect('/h5', true);

            return false;
        }

        return parent::beforeExecuteRoute($dispatcher);
    }

    /**
     * @Get("/", name="home.index")
     */
    public function indexAction()
    {
        $this->seo->setKeywords($this->siteInfo['keywords']);
        $this->seo->setDescription($this->siteInfo['description']);

        $type = $this->siteInfo['index_tpl_type'] ?? 'full';

        if ($type == 'full') {
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
        $this->view->setVar('slides', $service->getSlides());
        $this->view->setVar('featured_courses', $service->getFeaturedCourses());
        $this->view->setVar('new_courses', $service->getNewCourses());
        $this->view->setVar('free_courses', $service->getFreeCourses());
        $this->view->setVar('vip_courses', $service->getVipCourses());
    }

    protected function simpleIndex()
    {
        $service = new IndexService();

        $this->view->pick('index/simple');
        $this->view->setVar('lives', $service->getLives());
        $this->view->setVar('slides', $service->getSlides());
        $this->view->setVar('featured_courses', $service->getSimpleFeaturedCourses());
        $this->view->setVar('new_courses', $service->getSimpleNewCourses());
        $this->view->setVar('free_courses', $service->getSimpleFreeCourses());
        $this->view->setVar('vip_courses', $service->getSimpleVipCourses());
    }

    protected function h5Enabled()
    {
        $file = public_path('h5/index.html');

        return file_exists($file);
    }

}
