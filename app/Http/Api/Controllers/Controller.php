<?php

namespace App\Http\Api\Controllers;

use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    use ResponseTrait, SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->checkRateLimit();
    }

}
