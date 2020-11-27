<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Logic\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Logic\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Logic\Consult\ConsultLike as ConsultLikeService;
use App\Services\Logic\Consult\ConsultUpdate as ConsultUpdateService;

/**
 * @RoutePrefix("/api/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.consult.info")
     */
    public function infoAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        return $this->jsonSuccess(['consult' => $consult]);
    }

    /**
     * @Post("/create", name="api.consult.create")
     */
    public function createAction()
    {
        $service = new ConsultCreateService();

        $consult = $service->handle();

        $service = new ConsultInfoService();

        $consult = $service->handle($consult->id);

        return $this->jsonSuccess(['consult' => $consult]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="api.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $consult = $service->handle($id);

        $service = new ConsultInfoService();

        $consult = $service->handle($consult->id);

        return $this->jsonSuccess(['consult' => $consult]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="api.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.consult.like")
     */
    public function likeAction($id)
    {
        $service = new ConsultLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/unlike", name="api.consult.unlike")
     */
    public function unlikeAction($id)
    {
        $service = new ConsultLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
