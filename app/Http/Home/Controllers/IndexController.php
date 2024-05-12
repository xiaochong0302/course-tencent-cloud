<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Index as IndexService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;

class IndexController extends Controller
{

    /**
     * @Get("/", name="home.index")
     */
    public function indexAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getHomeUrl();
            return $this->response->redirect($location);
        }

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

}
