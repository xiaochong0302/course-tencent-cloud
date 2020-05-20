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
        $service = new CourseListService();

        $pager = $service->handle();

        $service = new CourseQueryService();

        $topCategories = $service->handleTopCategories();
        $subCategories = $service->handleSubCategories();

        $models = $service->handleModels();
        $levels = $service->handleLevels();
        $sorts = $service->handleSorts();

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
        $service = new CourseInfoService();

        $course = $service->handle($id);

        $service = new CourseQueryService();

        $categoryPaths = $service->handleCategoryPaths($course['category_id']);

        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        $service = new CoursePackageListService();

        $packages = $service->handle($id);

        $service = new CourseTeacherListService();

        $teachers = $service->handle($id);

        $service = new CourseTopicListService();

        $topics = $service->handle($id);

        $service = new CourseRecommendedListService();

        $recommendedCourses = $service->handle($id);

        $service = new CourseRelatedListService();

        $relatedCourses = $service->handle($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
        $this->view->setVar('packages', $packages);
        $this->view->setVar('teachers', $teachers);
        $this->view->setVar('topics', $topics);
        $this->view->setVar('recommended_courses', $recommendedCourses);
        $this->view->setVar('related_courses', $relatedCourses);
        $this->view->setVar('category_paths', $categoryPaths);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="web.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new CourseConsultListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="web.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="web.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $service->handle($id);

        return $this->jsonSuccess(['msg' => '收藏课程成功']);
    }

}
