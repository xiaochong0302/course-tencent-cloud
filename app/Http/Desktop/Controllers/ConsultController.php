<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Frontend\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Frontend\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Frontend\Consult\ConsultLike as ConsultLikeService;
use App\Services\Frontend\Consult\ConsultReply as ConsultReplyService;
use App\Services\Frontend\Consult\ConsultUpdate as ConsultUpdateService;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/add", name="desktop.consult.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/show", name="desktop.consult.show")
     */
    public function showAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="desktop.consult.edit")
     */
    public function editAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/create", name="desktop.consult.create")
     */
    public function createAction()
    {
        $service = new ConsultCreateService();

        $consult = $service->handle();

        $service = new ConsultInfoService();

        $service->handle($consult->id);

        $content = ['msg' => '提交咨询成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="desktop.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $service->handle($id);

        $content = ['msg' => '更新咨询成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="desktop.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        $content = ['msg' => '删除咨询成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Route("/{id:[0-9]+}/reply", name="desktop.consult.reply")
     */
    public function replyAction($id)
    {
        if ($this->request->isPost()) {

            $service = new ConsultReplyService();

            $service->handle($id);

            $content = ['msg' => '回复咨询成功'];

            return $this->jsonSuccess($content);

        } else {

            $service = new ConsultInfoService();

            $consult = $service->handle($id);

            $this->view->setVar('consult', $consult);
        }
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="desktop.consult.like")
     */
    public function likeAction($id)
    {
        $service = new ConsultLikeService();

        $like = $service->handle($id);

        $msg = $like->deleted == 0 ? '点赞成功' : '取消点赞成功';

        $content = ['msg' => $msg];

        return $this->jsonSuccess($content);
    }

}
