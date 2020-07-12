<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Frontend\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Frontend\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Frontend\Consult\ConsultLike as ConsultLikeService;
use App\Services\Frontend\Consult\ConsultUpdate as ConsultUpdateService;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="web.consult.info")
     */
    public function infoAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        return $this->jsonSuccess(['consult' => $consult]);
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

        return $this->jsonSuccess(['consult' => $consult]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $consult = $service->handle($id);

        return $this->jsonSuccess(['consult' => $consult]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
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
