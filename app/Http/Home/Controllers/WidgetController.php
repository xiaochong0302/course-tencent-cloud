<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Caches\FeaturedArticleList as FeaturedArticleListCache;
use App\Caches\FeaturedCourseList as FeaturedCourseListCache;
use App\Caches\FeaturedQuestionList as FeaturedQuestionListCache;
use App\Services\Logic\Article\TopAuthorList as TopAuthorListService;
use App\Services\Logic\Question\HotQuestionList as HotQuestionListService;
use App\Services\Logic\Question\TopAnswererList as TopAnswererListService;
use App\Services\Logic\Tag\FollowList as FollowListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/widget")
 */
class WidgetController extends Controller
{

    /**
     * @Get("/my/tags", name="home.widget.my_tags")
     */
    public function myTagsAction()
    {
        $service = new FollowListService();

        $pager = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/my_tags');
        $this->view->setVar('tags', $pager->items);
    }

    /**
     * @Get("/featured/courses", name="home.widget.featured_courses")
     */
    public function featuredCoursesAction()
    {
        $cache = new FeaturedCourseListCache();

        $courses = $cache->get();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/featured_courses');
        $this->view->setVar('courses', $courses);
    }

    /**
     * @Get("/featured/articles", name="home.widget.featured_articles")
     */
    public function featuredArticlesAction()
    {
        $cache = new FeaturedArticleListCache();

        $articles = $cache->get();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/featured_articles');
        $this->view->setVar('articles', $articles);
    }

    /**
     * @Get("/featured/questions", name="home.widget.featured_questions")
     */
    public function featuredQuestionsAction()
    {
        $cache = new FeaturedQuestionListCache();

        $questions = $cache->get();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/featured_questions');
        $this->view->setVar('questions', $questions);
    }

    /**
     * @Get("/hot/questions", name="home.widget.hot_questions")
     */
    public function hotQuestionsAction()
    {
        $service = new HotQuestionListService();

        $questions = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/hot_questions');
        $this->view->setVar('questions', $questions);
    }

    /**
     * @Get("/top/authors", name="home.widget.top_authors")
     */
    public function topAuthorsAction()
    {
        $service = new TopAuthorListService();

        $authors = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/top_authors');
        $this->view->setVar('authors', $authors);
    }

    /**
     * @Get("/top/answerers", name="home.widget.top_answerers")
     */
    public function topAnswerersAction()
    {
        $service = new TopAnswererListService();

        $answerers = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('widget/top_answerers');
        $this->view->setVar('answerers', $answerers);
    }

}
