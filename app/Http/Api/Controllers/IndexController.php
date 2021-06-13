<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Caches\IndexSimpleFeaturedCourseList;
use App\Caches\IndexSimpleFreeCourseList;
use App\Caches\IndexSimpleNewCourseList;
use App\Caches\IndexSimpleVipCourseList;
use App\Caches\IndexSlideList;

/**
 * @RoutePrefix("/api/index")
 */
class IndexController extends Controller
{

    /**
     * @Get("/slides", name="api.index.slides")
     */
    public function slidesAction()
    {
        $cache = new IndexSlideList();

        $slides = $cache->get();

        return $this->jsonSuccess(['slides' => $slides]);
    }

    /**
     * @Get("/courses/featured", name="api.index.featured_courses")
     */
    public function featuredCoursesAction()
    {
        $cache = new IndexSimpleFeaturedCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/courses/new", name="api.index.new_courses")
     */
    public function newCoursesAction()
    {
        $cache = new IndexSimpleNewCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/courses/free", name="api.index.free_courses")
     */
    public function freeCoursesAction()
    {
        $cache = new IndexSimpleFreeCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/courses/vip", name="api.index.vip_courses")
     */
    public function vipCoursesAction()
    {
        $cache = new IndexSimpleVipCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
