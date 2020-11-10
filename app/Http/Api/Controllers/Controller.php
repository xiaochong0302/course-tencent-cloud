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
        /**
         * 存在Origin头信息才设置跨域
         */
        if ($this->request->getHeader('Origin')) {
            $this->setCors();
        }

        /**
         * Options请求不验证签名和限流
         */
        if (!$this->request->isOptions()) {
            //$this->checkApiSignature();
            //$this->checkRateLimit();
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
