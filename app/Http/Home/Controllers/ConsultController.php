<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Logic\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Logic\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Logic\Consult\ConsultLike as ConsultLikeService;
use App\Services\Logic\Consult\ConsultReply as ConsultReplyService;
use App\Services\Logic\Consult\ConsultUpdate as ConsultUpdateService;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/add", name="home.consult.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/show", name="home.consult.show")
     */
    public function showAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.consult.edit")
     */
    public function editAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/create", name="home.consult.create")
     */
    public function createAction()
    {
        $service = new ConsultCreateService();

        $consult = $service->handle();

        $service = new ConsultInfoService();

        $consult = $service->handle($consult->id);

        $content = [
            'consult' => $consult,
            'msg' => '提交咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $consult = $service->handle($id);

        $service = new ConsultInfoService();

        $consult = $service->handle($consult->id);

        $content = [
            'consult' => $consult,
            'msg' => '更新咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        return $this->jsonSuccess(['msg' => '删除咨询成功']);
    }

    /**
     * @Route("/{id:[0-9]+}/reply", name="home.consult.reply")
     */
    public function replyAction($id)
    {
        if ($this->request->isPost()) {

            $service = new ConsultReplyService();

            $consult = $service->handle($id);

            $service = new ConsultInfoService();

            $consult = $service->handle($consult->id);

            $content = [
                'consult' => $consult,
                'msg' => '回复咨询成功',
            ];

            return $this->jsonSuccess($content);

        } else {

            $service = new ConsultInfoService();

            $consult = $service->handle($id);

            $this->view->setVar('consult', $consult);
        }
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.consult.like")
     */
    public function likeAction($id)
    {
        $service = new ConsultLikeService();

        $like = $service->handle($id);

        $msg = $like->deleted == 0 ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['msg' => $msg]);
    }

}
