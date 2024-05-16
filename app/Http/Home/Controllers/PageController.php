<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Index as IndexService;
use App\Services\Logic\Page\PageInfo as PageInfoService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;

/**
 * @RoutePrefix("/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id}", name="home.page.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getPageInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new PageInfoService();

        $page = $service->handle($id);

        if ($page['deleted'] == 1) {
            $this->notFound();
        }

        if ($page['published'] == 0) {
            $this->notFound();
        }

        $featuredCourses = $this->getFeaturedCourses();

        $this->seo->prependTitle(['单页', $page['title']]);

        $this->view->setVar('page', $page);
        $this->view->setVar('featured_courses', $featuredCourses);
    }

    protected function getFeaturedCourses()
    {
        $service = new IndexService();

        return $service->getSimpleFeaturedCourses();
    }

}
