<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Answer as AnswerService;

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
     * @Post("/{id:[0-9]+}/update", name="admin.answer.update")
     */
    public function updateAction($id)
    {
        $answerService = new AnswerService();

        $answerService->updateAnswer($id);

        $content = ['msg' => '更新回答成功'];

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

}
