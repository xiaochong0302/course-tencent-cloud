<?php

namespace App\Http\Web\Controllers;

use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/error")
 */
class ErrorController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    public function initialize()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/400", name="web.error.400")
     */
    public function show400Action()
    {
        $this->response->setStatusCode(400);
    }

    /**
     * @Get("/401", name="web.error.401")
     */
    public function show401Action()
    {
        $this->response->setStatusCode(401);
    }

    /**
     * @Get("/403", name="web.error.403")
     */
    public function show403Action()
    {
        $this->response->setStatusCode(403);
    }

    /**
     * @Get("/404", name="web.error.404")
     */
    public function show404Action()
    {
        $this->response->setStatusCode(404);

        $isApiRequest = is_api_request();
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest || $isApiRequest) {
            return $this->jsonError(['code' => 'sys.uri_not_found']);
        }
    }

    /**
     * @Get("/500", name="web.error.500")
     */
    public function show500Action()
    {
        $this->response->setStatusCode(500);
    }

}
