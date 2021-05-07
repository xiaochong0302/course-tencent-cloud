<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Question as QuestionService;
use App\Http\Home\Services\QuestionQuery as QuestionQueryService;
use App\Services\Logic\Question\AnswerList as AnswerListService;
use App\Services\Logic\Question\HotQuestionList as HotQuestionListService;
use App\Services\Logic\Question\QuestionCreate as QuestionCreateService;
use App\Services\Logic\Question\QuestionDelete as QuestionDeleteService;
use App\Services\Logic\Question\QuestionFavorite as QuestionFavoriteService;
use App\Services\Logic\Question\QuestionInfo as QuestionInfoService;
use App\Services\Logic\Question\QuestionLike as QuestionLikeService;
use App\Services\Logic\Question\QuestionList as QuestionListService;
use App\Services\Logic\Question\QuestionUpdate as QuestionUpdateService;
use App\Services\Logic\Question\RelatedQuestionList as RelatedQuestionListService;
use App\Services\Logic\Question\TopAnswererList as TopAnswererListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/question")
 */
class QuestionController extends Controller
{

    /**
     * @Get("/hot/questions", name="home.question.hot_questions")
     */
    public function hotQuestionsAction()
    {
        $service = new HotQuestionListService();

        $questions = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('question/hot_questions');
        $this->view->setVar('questions', $questions);
    }

    /**
     * @Get("/top/answerers", name="home.question.top_answerers")
     */
    public function topAnswerersAction()
    {
        $service = new TopAnswererListService();

        $answerers = $service->handle();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('question/top_answerers');
        $this->view->setVar('answerers', $answerers);
    }

    /**
     * @Get("/list", name="home.question.list")
     */
    public function listAction()
    {
        $service = new QuestionQueryService();

        $sorts = $service->handleSorts();
        $params = $service->getParams();

        $this->seo->prependTitle('问答');

        $this->view->setVar('sorts', $sorts);
        $this->view->setVar('params', $params);
    }

    /**
     * @Get("/pager", name="home.question.pager")
     */
    public function pagerAction()
    {
        $service = new QuestionListService();

        $pager = $service->handle();

        $pager->target = 'question-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="home.question.add")
     */
    public function addAction()
    {
        $service = new QuestionService();

        $question = $service->getQuestionModel();

        $xmTags = $service->getXmTags(0);

        $this->seo->prependTitle('提问题');

        $this->view->pick('question/edit');
        $this->view->setVar('question', $question);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.question.edit")
     */
    public function editAction($id)
    {
        $service = new QuestionService();

        $question = $service->getQuestion($id);

        $xmTags = $service->getXmTags($id);

        $this->seo->prependTitle('编辑问题');

        $this->view->setVar('question', $question);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.question.show")
     */
    public function showAction($id)
    {
        $service = new QuestionInfoService();

        $question = $service->handle($id);

        $this->seo->prependTitle($question['title']);

        $this->view->setVar('question', $question);
    }

    /**
     * @Get("/{id:[0-9]+}/answers", name="home.question.answers")
     */
    public function answersAction($id)
    {
        $service = new QuestionService();

        $question = $service->getQuestion($id);

        $service = new AnswerListService();

        $pager = $service->handle($id);

        $pager->target = 'answer-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('question', $question);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/related", name="home.question.related")
     */
    public function relatedAction($id)
    {
        $service = new RelatedQuestionListService();

        $questions = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('questions', $questions);
    }

    /**
     * @Post("/create", name="home.question.create")
     */
    public function createAction()
    {
        $service = new QuestionCreateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.questions']);

        $content = [
            'location' => $location,
            'msg' => '创建问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.question.update")
     */
    public function updateAction($id)
    {
        $service = new QuestionUpdateService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.questions']);

        $content = [
            'location' => $location,
            'msg' => '更新问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.question.delete")
     */
    public function deleteAction($id)
    {
        $service = new QuestionDeleteService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.questions']);

        $content = [
            'location' => $location,
            'msg' => '删除问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="home.question.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new QuestionFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.question.like")
     */
    public function likeAction($id)
    {
        $service = new QuestionLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
