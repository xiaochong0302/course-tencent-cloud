<?php

namespace App\Http\Home\Controllers;

use App\Traits\Ajax as AjaxTrait;
use App\Traits\Security as SecurityTrait;

class Controller extends \Phalcon\Mvc\Controller
{

    use AjaxTrait, SecurityTrait;

    public function initialize()
    {
        $controllerName = $this->router->getControllerName();

        if ($controllerName != 'index') {
            //$this->request->checkReferer();
        }

        if ($this->request->isPost()) {
            //$this->request->checkToken();
        }
    }

}
