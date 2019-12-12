<?php

namespace App\Http\Home\Controllers;

use App\Traits\Ajax as AjaxTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/error")
 */
class ErrorController extends \Phalcon\Mvc\Controller
{

    use AjaxTrait;

    public function initialize()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/400", name="error.400") 
     */
    public function show400Action()
    {
        $this->response->setStatusCode(400);
    }

    /**
     * @Get("/401", name="error.401") 
     */
    public function show401Action()
    {
        $this->response->setStatusCode(401);
    }

    /**
     * @Get("/403", name="error.403") 
     */
    public function show403Action()
    {
        $this->response->setStatusCode(403);
    }

    /**
     * @Get("/404", name="error.404") 
     */
    public function show404Action()
    {
        $this->response->setStatusCode(404);
        
        if ($this->request->isAjax()) {
            return $this->ajaxError(['code' => 'sys.uri_not_found']);
        }
    }

    /**
     * @Get("/500", name="error.500") 
     */
    public function show500Action()
    {
        $this->response->setStatusCode(500);
    }

}
