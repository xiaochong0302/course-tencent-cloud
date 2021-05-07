<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Answer as AnswerService;
use App\Services\Logic\Answer\AnswerAccept as AnswerAcceptService;
use App\Services\Logic\Answer\AnswerCreate as AnswerCreateService;
use App\Services\Logic\Answer\AnswerDelete as AnswerDeleteService;
use App\Services\Logic\Answer\AnswerInfo as AnswerInfoService;
use App\Services\Logic\Answer\AnswerLike as AnswerLikeService;
use App\Services\Logic\Answer\AnswerUpdate as AnswerUpdateService;

/**
 * @RoutePrefix("/answer")
 */
class AnswerController extends Controller
{

    /**
     * @Get("/add", name="home.answer.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.answer.edit")
     */
    public function editAction($id)
    {
        $service = new AnswerService();

        $answer = $service->getAnswer($id);

        $this->view->setVar('answer', $answer);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.answer.show")
     */
    public function showAction($id)
    {
        $service = new AnswerInfoService();

        $answer = $service->handle($id);

        $this->view->setVar('answer', $answer);
    }

    /**
     * @Post("/create", name="home.answer.create")
     */
    public function createAction()
    {
        $service = new AnswerCreateService();

        $service->handle();

        $content = ['msg' => '创建答案成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.answer.update")
     */
    public function updateAction($id)
    {
        $service = new AnswerUpdateService();

        $service->handle($id);

        $content = ['msg' => '更新答案成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.answer.delete")
     */
    public function deleteAction($id)
    {
        $service = new AnswerDeleteService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.answers']);

        $content = [
            'location' => $location,
            'msg' => '删除答案成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.answer.like")
     */
    public function likeAction($id)
    {
        $service = new AnswerLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/accept", name="home.answer.accept")
     */
    public function acceptAction($id)
    {
        $service = new AnswerAcceptService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '采纳成功' : '取消采纳成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
