<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Frontend\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Frontend\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Frontend\Consult\ConsultLike as ConsultLikeService;
use App\Services\Frontend\Consult\ConsultUpdate as ConsultUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/add", name="web.consult.add")
     */
    public function addAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="web.consult.edit")
     */
    public function editAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('consult', $consult);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="web.consult.show")
     */
    public function showAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/create", name="web.consult.create")
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
     * @Post("/{id:[0-9]+}/update", name="web.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $consult = $service->handle($id);

        $content = [
            'consult' => $consult,
            'msg' => '更新咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        $content = ['msg' => '删除咨询成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="web.consult.like")
     */
    public function likeAction($id)
    {
        $service = new ConsultLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
