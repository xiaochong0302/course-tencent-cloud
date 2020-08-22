<?php

namespace App\Http\Desktop\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/error")
 */
class ErrorController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/400", name="desktop.error.400")
     */
    public function show400Action()
    {
        $this->response->setStatusCode(400);
    }

    /**
     * @Get("/401", name="desktop.error.401")
     */
    public function show401Action()
    {
        $this->response->setStatusCode(401);
    }

    /**
     * @Get("/403", name="desktop.error.403")
     */
    public function show403Action()
    {
        $this->response->setStatusCode(403);
    }

    /**
     * @Get("/404", name="desktop.error.404")
     */
    public function show404Action()
    {
        $this->response->setStatusCode(404);

        $isAjaxRequest = $this->request->isAjax();
        $isApiRequest = $this->request->isApi();

        if ($isAjaxRequest || $isApiRequest) {
            return $this->jsonError(['code' => 'sys.not_found']);
        }
    }

    /**
     * @Get("/500", name="desktop.error.500")
     */
    public function show500Action()
    {
        $this->response->setStatusCode(500);
    }

    /**
     * @Get("/503", name="desktop.error.503")
     */
    public function show503Action()
    {
        $this->response->setStatusCode(503);
    }

    /**
     * @Get("/shutdown", name="desktop.error.shutdown")
     */
    public function shutdownAction()
    {
        $message = $this->dispatcher->getParam('message');

        $this->response->setStatusCode(503);

        $this->view->setVar('message', $message);
    }

}
