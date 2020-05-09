<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\CourseQuery as CourseQueryService;
use App\Services\Frontend\Course\ChapterList as CourseChapterListService;
use App\Services\Frontend\Course\ConsultList as CourseConsultListService;
use App\Services\Frontend\Course\CourseInfo as CourseInfoService;
use App\Services\Frontend\Course\CourseList as CourseListService;
use App\Services\Frontend\Course\Favorite as CourseFavoriteService;
use App\Services\Frontend\Course\PackageList as CoursePackageListService;
use App\Services\Frontend\Course\RecommendedList as CourseRecommendedListService;
use App\Services\Frontend\Course\RelatedList as CourseRelatedListService;
use App\Services\Frontend\Course\ReviewList as CourseReviewListService;
use App\Services\Frontend\Course\TeacherList as CourseTeacherListService;
use App\Services\Frontend\Course\TopicList as CourseTopicListService;

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

        $courseQueryService = new CourseQueryService();

        $topCategories = $courseQueryService->handleTopCategories();
        $subCategories = $courseQueryService->handleSubCategories();
        $models = $courseQueryService->handleModels();
        $levels = $courseQueryService->handleLevels();
        $sorts = $courseQueryService->handleSorts();

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
        $this->view->setVar('models', $models);
        $this->view->setVar('levels', $levels);
        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="web.course.show")
     */
    public function showAction($id)
    {
        $courseInfoService = new CourseInfoService();

        $course = $courseInfoService->handle($id);

        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/{id:[0-9]+}/teachers", name="web.course.teachers")
     */
    public function teachersAction($id)
    {
        $service = new CourseTeacherListService();

        $teachers = $service->handle($id);

        return $this->jsonSuccess(['teachers' => $teachers]);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="web.course.chapters")
     */
    public function chaptersAction($id)
    {
        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        return $this->jsonSuccess(['chapters' => $chapters]);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="web.course.packages")
     */
    public function packagesAction($id)
    {
        $service = new CoursePackageListService();

        $packages = $service->handle($id);

        return $this->jsonSuccess(['packages' => $packages]);
    }

    /**
     * @Get("/{id:[0-9]+}/recommended", name="web.course.recommended")
     */
    public function recommendedAction($id)
    {
        $service = new CourseRecommendedListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="web.course.related")
     */
    public function relatedAction($id)
    {
        $service = new CourseRelatedListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

    /**
     * @Get("/{id:[0-9]+}/topics", name="web.course.topics")
     */
    public function topicsAction($id)
    {
        $service = new CourseTopicListService();

        $topics = $service->handle($id);

        return $this->jsonSuccess(['topics' => $topics]);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="web.course.consults")
     */
    public function consultsAction($id)
    {
        $consultListService = new CourseConsultListService();

        $pager = $consultListService->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="web.course.reviews")
     */
    public function reviewsAction($id)
    {
        $reviewListService = new CourseReviewListService();

        $pager = $reviewListService->handle($id);

        return $this->jsonPaginate($pager);
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
