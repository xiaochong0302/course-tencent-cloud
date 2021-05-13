<?php

namespace App\Http\Home\Controllers;

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
