<?php

namespace App\Http\Home\Controllers;

use App\Services\Frontend\Course as CourseService;
use App\Services\Frontend\CourseFavorite as CourseFavoriteService;
use App\Services\Frontend\CourseList as CourseListService;
use App\Services\Frontend\CourseRelated as CourseRelatedService;
use App\Services\Frontend\CourseReview as CourseReviewService;

/**
 * @RoutePrefix("/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/list", name="home.course.list")
     */
    public function listAction()
    {
        $courseListService = new CourseListService();

        $pager = $courseListService->getCourses();

        return $this->ajaxSuccess(['pager' => $pager]);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.course.show")
     */
    public function showAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);

        return $this->ajaxSuccess(['course' => $course]);

        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.course.related")
     */
    public function relatedAction($id)
    {
        $relatedService = new CourseRelatedService();

        $courses = $relatedService->getRelated($id);

        return $this->ajaxSuccess(['courses' => $courses]);

        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="home.course.reviews")
     */
    public function reviewsAction($id)
    {
        $reviewService = new CourseReviewService();

        $pager = $reviewService->getReviews($id);

        return $this->ajaxSuccess(['pager' => $pager]);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.course.favorite")
     */
    public function favoriteAction($id)
    {
        $favoriteService = new CourseFavoriteService();

        $favoriteService->saveFavorite($id);

        return $this->response->ajaxSuccess();
    }

}
