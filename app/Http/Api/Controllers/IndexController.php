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
use App\Caches\IndexTeacherList;

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
     * @Get("/teachers", name="api.index.teachers")
     */
    public function teachersAction()
    {
        $cache = new IndexTeacherList();

        $teachers = $cache->get();

        return $this->jsonSuccess(['teachers' => $teachers]);
    }

    /**
     * @Get("/featured-courses", name="api.index.featured_courses")
     */
    public function featuredCoursesAction()
    {
        $cache = new IndexSimpleFeaturedCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/new-courses", name="api.index.new_courses")
     */
    public function newCoursesAction()
    {
        $cache = new IndexSimpleNewCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/free-courses", name="api.index.free_courses")
     */
    public function freeCoursesAction()
    {
        $cache = new IndexSimpleFreeCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/vip-courses", name="api.index.vip_courses")
     */
    public function vipCoursesAction()
    {
        $cache = new IndexSimpleVipCourseList();

        $courses = $cache->get();

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
