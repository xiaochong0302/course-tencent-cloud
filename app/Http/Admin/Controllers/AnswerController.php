<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Answer as AnswerService;
use App\Http\Admin\Services\Question as QuestionService;

/**
 * @RoutePrefix("/admin/answer")
 */
class AnswerController extends Controller
{

    /**
     * @Get("/search", name="admin.answer.search")
     */
    public function searchAction()
    {
        $answerService = new AnswerService();

        $publishTypes = $answerService->getPublishTypes();

        $this->view->setVar('publish_types', $publishTypes);
    }

    /**
     * @Get("/list", name="admin.answer.list")
     */
    public function listAction()
    {
        $answerService = new AnswerService();

        $pager = $answerService->getAnswers();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.answer.add")
     */
    public function addAction()
    {
        $id = $this->request->getQuery('question_id', 'int', 0);

        $questionService = new QuestionService();

        $question = $questionService->getQuestion($id);

        $referer = $this->request->getHTTPReferer();

        $this->view->setVar('question', $question);
        $this->view->setVar('referer', $referer);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.answer.edit")
     */
    public function editAction($id)
    {
        $answerService = new AnswerService();

        $answer = $answerService->getAnswer($id);

        $questionService = new QuestionService();

        $question = $questionService->getQuestion($answer->question_id);

        $referer = $this->request->getHTTPReferer();

        $this->view->setVar('referer', $referer);
        $this->view->setVar('question', $question);
        $this->view->setVar('answer', $answer);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.answer.show")
     */
    public function showAction($id)
    {
        $answerService = new AnswerService();

        $answer = $answerService->getAnswer($id);

        $this->view->setVar('answer', $answer);
    }

    /**
     * @Post("/create", name="admin.answer.create")
     */
    public function createAction()
    {
        $answerService = new AnswerService();

        $answerService->createAnswer();

        $location = $this->request->getPost('referer');

        if (empty($location)) {
            $location = $this->url->get(['for' => 'admin.question.list']);
        }

        $content = [
            'location' => $location,
            'msg' => '回答问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.answer.update")
     */
    public function updateAction($id)
    {
        $answerService = new AnswerService();

        $answerService->updateAnswer($id);

        $location = $this->request->getPost('referer');

        if (empty($location)) {
            $location = $this->url->get(['for' => 'admin.answer.list']);
        }

        $content = [
            'location' => $location,
            'msg' => '更新回答成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.answer.delete")
     */
    public function deleteAction($id)
    {
        $answerService = new AnswerService();

        $answerService->deleteAnswer($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除回答成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.answer.restore")
     */
    public function restoreAction($id)
    {
        $answerService = new AnswerService();

        $answerService->restoreAnswer($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原回答成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Route("/{id:[0-9]+}/review", name="admin.answer.review")
     */
    public function reviewAction($id)
    {
        $answerService = new AnswerService();

        $answer = $answerService->getAnswer($id);

        if ($this->request->isPost()) {

            $answerService->reviewAnswer($id);

            $location = $this->url->get(['for' => 'admin.mod.answers']);

            $content = [
                'location' => $location,
                'msg' => '审核回答成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $answerService->getReasons();

        $questionService = new QuestionService();

        $question = $questionService->getQuestion($answer->question_id);

        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('question', $question);
        $this->view->setVar('answer', $answer);
    }

}
