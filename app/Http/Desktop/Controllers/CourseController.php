<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\CourseQuery as CourseQueryService;
use App\Services\Frontend\Course\ChapterList as CourseChapterListService;
use App\Services\Frontend\Course\ConsultList as CourseConsultListService;
use App\Services\Frontend\Course\CourseInfo as CourseInfoService;
use App\Services\Frontend\Course\CourseList as CourseListService;
use App\Services\Frontend\Course\Favorite as CourseFavoriteService;
use App\Services\Frontend\Course\PackageList as CoursePackageListService;
use App\Services\Frontend\Course\RecommendedList as CourseRecommendedListService;
use App\Services\Frontend\Course\RelatedList as CourseRelatedListService;
use App\Services\Frontend\Course\ReviewList as CourseReviewListService;
use App\Services\Frontend\Course\TopicList as CourseTopicListService;
use App\Services\Frontend\Reward\OptionList as RewardOptionList;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/list", name="desktop.course.list")
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

        $this->seo->prependTitle('课程');

        $this->view->setVar('top_categories', $topCategories);
        $this->view->setVar('sub_categories', $subCategories);
        $this->view->setVar('models', $models);
        $this->view->setVar('levels', $levels);
        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('params', $params);
    }

    /**
     * @Get("/pager", name="desktop.course.pager")
     */
    public function pagerAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="desktop.course.show")
     */
    public function showAction($id)
    {
        $service = new CourseInfoService();

        $course = $service->handle($id);

        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        $service = new RewardOptionList();

        $rewards = $service->handle();

        $this->seo->prependTitle(['课程', $course['title']]);
        $this->seo->setKeywords($course['keywords']);
        $this->seo->setDescription($course['summary']);

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
        $this->view->setVar('rewards', $rewards);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="desktop.course.packages")
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
     * @Get("/{id:[0-9]+}/consults", name="desktop.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new CourseConsultListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-consults';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/consults');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="desktop.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-reviews';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('course/reviews');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/recommended", name="desktop.course.recommended")
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
     * @Get("/{id:[0-9]+}/related", name="desktop.course.related")
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
     * @Get("/{id:[0-9]+}/topics", name="desktop.course.topics")
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
     * @Post("/{id:[0-9]+}/favorite", name="desktop.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $favorite = $service->handle($id);

        $msg = $favorite->deleted == 0 ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['msg' => $msg]);
    }

}
