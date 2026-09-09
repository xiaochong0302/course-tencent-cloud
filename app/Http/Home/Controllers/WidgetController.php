<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Caches\FeaturedCourseList as FeaturedCourseListCache;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/widget")
 */
class WidgetController extends Controller
{

    /**
     * @Get("/featured-courses", name="home.widget.featured_courses")
     */
    public function featuredCoursesAction()
    {
        $cache = new FeaturedCourseListCache();

        $courses = $cache->get();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/featured_courses');
        $this->view->setVar('courses', $courses);
    }

}
