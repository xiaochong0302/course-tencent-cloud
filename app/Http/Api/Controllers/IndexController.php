<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Caches\IndexArticleList;
use App\Caches\IndexLiveList;
use App\Caches\IndexQuestionList;
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
     * @Get("/articles", name="api.index.articles")
     */
    public function articlesAction()
    {
        $cache = new IndexArticleList();

        $articles = $cache->get();

        return $this->jsonSuccess(['articles' => $articles]);
    }

    /**
     * @Get("/questions", name="api.index.questions")
     */
    public function questionsAction()
    {
        $cache = new IndexQuestionList();

        $questions = $cache->get();

        return $this->jsonSuccess(['questions' => $questions]);
    }

    /**
     * @Get("/lives", name="api.index.lives")
     */
    public function livesAction()
    {
        $cache = new IndexLiveList();

        $lives = $cache->get();

        return $this->jsonSuccess(['lives' => $lives]);
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
