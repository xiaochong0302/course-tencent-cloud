<?php

namespace App\Http\Api\Controllers;

use App\Services\Auth\Api as AppAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->getHeader('Origin')) {
            $this->setCors();
        }

        if (!$this->request->isOptions()) {
            $this->checkRateLimit();
        }

        return true;
    }

    protected function getAuthUser()
    {
        /**
         * @var AppAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
