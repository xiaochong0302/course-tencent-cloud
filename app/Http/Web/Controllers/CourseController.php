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
use App\Services\Frontend\Reward\OptionList as RewardOptionList;
use Phalcon\Mvc\View;

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
        $service = new CourseQueryService();

        $topCategories = $service->handleTopCategories();
        $subCategories = $service->handleSubCategories();

        $models = $service->handleModels();
        $levels = $service->handleLevels();
        $sorts = $service->handleSorts();
        $params = $service->getParams();

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
        $this->view->setVar('models', $models);
        $this->view->setVar('levels', $levels);
        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('params', $params);
    }

    /**
     * @Get("/pager", name="web.course.pager")
     */
    public function pagerAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/pager');
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

        $course['category_paths'] = $service->handleCategoryPaths($course['category_id']);

        $service = new RewardOptionList();

        $rewards = $service->handle();

        $this->siteSeo->prependTitle($course['title']);
        $this->siteSeo->setKeywords($course['keywords']);
        $this->siteSeo->setDescription($course['summary']);

        $this->view->setVar('course', $course);
        $this->view->setVar('rewards', $rewards);
    }

    /**
     * @Get("/{id:[0-9]+}/teachers", name="web.course.teachers")
     */
    public function teachersAction($id)
    {
        $service = new CourseTeacherListService();

        $teachers = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/teachers');
        $this->view->setVar('teachers', $teachers);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="web.course.chapters")
     */
    public function chaptersAction($id)
    {
        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/chapters');
        $this->view->setVar('chapters', $chapters);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="web.course.packages")
     */
    public function packagesAction($id)
    {
        $service = new CoursePackageListService();

        $packages = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/packages');
        $this->view->setVar('packages', $packages);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="web.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new CourseConsultListService();

        $pager = $service->handle($id);
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'tab-consults';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/consults');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="web.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);
        $pager->items = kg_array_object($pager->items);
        $pager->target = 'tab-reviews';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/reviews');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/recommended", name="web.course.recommended")
     */
    public function recommendedAction($id)
    {
        $service = new CourseRecommendedListService();

        $courses = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/recommended');
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="web.course.related")
     */
    public function relatedAction($id)
    {
        $service = new CourseRelatedListService();

        $courses = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/related');
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Get("/{id:[0-9]+}/topics", name="web.course.topics")
     */
    public function topicsAction($id)
    {
        $service = new CourseTopicListService();

        $topics = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/topics');
        $this->view->setVar('topics', $topics);
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
