<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Index as IndexService;
use App\Services\Logic\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.page.show")
     */
    public function showAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        $featuredCourses = $this->getFeaturedCourses();

        $this->seo->prependTitle($page['title']);

        $this->view->setVar('page', $page);
        $this->view->setVar('featured_courses', $featuredCourses);
    }

    protected function getFeaturedCourses()
    {
        $service = new IndexService();

        return $service->getSimpleFeaturedCourses();
    }

}
