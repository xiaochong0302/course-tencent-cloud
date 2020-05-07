<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Course as CourseService;
use App\Services\Frontend\Course\ConsultList as CourseConsultListService;
use App\Services\Frontend\Course\CourseFavorite as CourseFavoriteService;
use App\Services\Frontend\Course\CourseInfo as CourseInfoService;
use App\Services\Frontend\Course\CourseList as CourseListService;
use App\Services\Frontend\Course\CourseRelated as CourseRelatedService;
use App\Services\Frontend\Course\ReviewList as CourseReviewListService;

/**
 * @RoutePrefix("/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/list", name="web.course.list")
     */
    public function listAction()
    {
        $courseListService = new CourseListService();

        $pager = $courseListService->handle();

        $courseService = new CourseService();

        $topCategories = $courseService->handleTopCategories();
        $subCategories = $courseService->handleSubCategories();
        $levels = $courseService->handleLevels();

        dd($topCategories, $subCategories, $levels);

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
        $this->view->setVar('levels', $levels);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.course.show")
     */
    public function showAction($id)
    {
        $courseInfoService = new CourseInfoService();

        $courseInfo = $courseInfoService->handle($id);

        $courseRelatedService = new CourseRelatedService();

        $relatedCourses = $courseRelatedService->handle($id);

        $this->view->setVar('course_info', $courseInfo);
        $this->view->setVar('related_courses', $relatedCourses);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="web.course.consults")
     */
    public function consultsAction($id)
    {
        $consultListService = new CourseConsultListService();

        $pager = $consultListService->handle($id);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="web.course.reviews")
     */
    public function reviewsAction($id)
    {
        $reviewListService = new CourseReviewListService();

        $pager = $reviewListService->handle($id);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="web.course.favorite")
     */
    public function favoriteAction($id)
    {
        $favoriteService = new CourseFavoriteService();

        $favoriteService->handle($id);

        return $this->jsonSuccess(['msg' => '收藏课程成功']);
    }

}
