<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Consult as ConsultService;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Post("/create", name="home.consult.create")
     */
    public function createAction()
    {
        $service = new ConsultService();

        $consult = $service->create();

        $data = $service->getConsult($consult->id);

        return $this->ajaxSuccess($data);
    }

    /**
     * @Get("/{id}", name="home.consult.show")
     */
    public function showAction($id)
    {
        $service = new ConsultService();

        $consult = $service->getConsult($id);

        return $this->ajaxSuccess($consult);
    }

    /**
     * @Post("/{id}/update", name="home.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultService();

        $consult = $service->update($id);

        $data = $service->getConsult($consult->id);

        return $this->ajaxSuccess($data);
    }

    /**
     * @Post("/{id}/delete", name="home.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultService();

        $service->delete($id);

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/{id}/agree", name="home.consult.agree")
     */
    public function agreeAction($id)
    {
        $service = new ConsultService();

        $service->agree($id);

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/{id}/oppose", name="home.consult.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ConsultService();

        $service->oppose($id);

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/{id}/reply", name="home.consult.reply")
     */
    public function replyAction($id)
    {
        $service = new ConsultService();

        $service->reply($id);

        return $this->ajaxSuccess();
    }

}
