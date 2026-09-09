<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\CourseQuery as CourseQueryService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;
use App\Services\Logic\Course\CourseFavorite as CourseFavoriteService;
use App\Services\Logic\Course\CourseInfo as CourseInfoService;
use App\Services\Logic\Course\CourseList as CourseListService;
use App\Services\Logic\Course\LastStudyChapter as LastStudyChapterService;
use App\Services\Logic\Course\RelatedList as CourseRelatedListService;
use App\Services\Logic\Course\ReviewList as CourseReviewListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
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
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getCourseListUrl();
            return $this->response->redirect($location);
        }

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
     * @Get("/pager", name="home.course.pager")
     */
    public function pagerAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.course.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($this->isMobileBrowser() && $this->h5Enabled()) {
            $location = $service->getCourseInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new CourseInfoService();

        $course = $service->handle($id);

        if ($course['deleted'] == 1) {
            $this->notFound();
        }

        if ($course['published'] == 0) {
            $this->notFound();
        }

        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        $this->seo->prependTitle(['课程', $course['title']]);
        $this->seo->keywords = $course['keywords'];
        $this->seo->description = $course['summary'];

        $this->view->setVar('course', $course);
        $this->view->setVar('chapters', $chapters);
    }

    /**
     * @Get("/{id:[0-9]+}/study", name="home.course.study")
     */
    public function studyAction($id)
    {
        $service = new LastStudyChapterService();

        $chapterId = $service->handle($id);

        if ($chapterId == 0) {
            $this->notFound();
        }

        $location = ['for' => 'home.chapter.show', 'id' => $chapterId];

        return $this->response->redirect($location);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="home.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-reviews';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.course.related")
     */
    public function relatedAction($id)
    {
        $service = new CourseRelatedListService();

        $courses = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
