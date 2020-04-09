<?php

namespace App\Http\Html5\Controllers;

use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    use ResponseTrait, SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if (!$this->checkRateLimit()) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'throttle',
            ]);
            return false;
        }
    }

}
