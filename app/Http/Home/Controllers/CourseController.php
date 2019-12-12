<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Course as CourseService;
use Phalcon\Mvc\View;

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
        $service = new CourseService();

        $courses = $service->getCourses();

        $this->view->courses = $courses;
    }

    /**
     * @Get("/{id}", name="home.course.show")
     */
    public function showAction($id)
    {
        $service = new CourseService();

        $course = $service->getCourse($id);

        $this->view->course = $course;
    }

    /**
     * @Get("/{id}/chapters", name="home.course.chapters")
     */
    public function chaptersAction($id)
    {
        $service = new CourseService();

        $chapters = $service->getChapters($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->chapters = $chapters;
    }

    /**
     * @Get("/{id}/consults", name="home.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new CourseService();

        $consults = $service->getConsults($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->consults = $consults;
    }

    /**
     * @Get("/{id}/reviews", name="home.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseService();

        $reviews = $service->getReviews($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->reviews = $reviews;
    }

    /**
     * @Get("/{id}/comments", name="home.course.comments")
     */
    public function commentsAction($id)
    {
        $service = new CourseService();

        $comments = $service->getComments($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->comments = $comments;
    }

    /**
     * @Get("/{id}/users", name="home.course.users")
     */
    public function usersAction($id)
    {
        $service = new CourseService();

        $users = $service->getUsers($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->users = $users;
    }

    /**
     * @Post("/{id}/favorite", name="home.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseService();

        $service->favorite($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/unfavorite", name="home.course.unfavorite")
     */
    public function unfavoriteAction($id)
    {
        $service = new CourseService();

        $service->unfavorite($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/apply", name="home.course.apply")
     */
    public function applyAction($id)
    {
        $service = new CourseService();

        $service->apply($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Get("/{id}/start", name="home.course.start")
     */
    public function startAction($id)
    {
        $service = new CourseService();

        $chapter = $service->getStartChapter($id);

        $this->response->redirect([
            'for' => 'home.chapter.show',
            'id' => $chapter->id,
        ]);
    }

}
