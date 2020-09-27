<?php

namespace App\Http\Admin\Controllers;

use App\Models\Audit as AuditModel;
use App\Services\Auth\Admin as AdminAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var array
     */
    protected $authInfo;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkRateLimit();

        $this->authInfo = $this->getAuthInfo();

        if (!$this->authInfo) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'auth',
            ]);
            return false;
        }

        /**
         * 管理员忽略权限检查
         */
        if ($this->authInfo['root'] == 1) {
            return true;
        }

        /**
         * 特例白名单
         */
        $whitelist = [
            'controllers' => ['public', 'index', 'vod', 'test', 'xm_course'],
            'routes' => ['admin.package.guiding'],
        ];

        $controller = $dispatcher->getControllerName();

        /**
         * 特定控制器忽略权限检查
         */
        if (in_array($controller, $whitelist['controllers'])) {
            return true;
        }

        $route = $this->router->getMatchedRoute();

        /**
         * 特定路由忽略权限检查
         */
        if (in_array($route->getName(), $whitelist['routes'])) {
            return true;
        }

        /**
         * 执行路由权限检查
         */
        if (!in_array($route->getName(), $this->authInfo['routes'])) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'forbidden',
            ]);
            return false;
        }

        return true;
    }

    public function initialize()
    {
        $this->view->setVar('auth_info', $this->authInfo);
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isPost()) {

            $audit = new AuditModel();

            $audit->user_id = $this->authInfo['id'];
            $audit->user_name = $this->authInfo['name'];
            $audit->user_ip = $this->request->getClientAddress();
            $audit->req_route = $this->router->getMatchedRoute()->getName();
            $audit->req_path = $this->request->getServer('REQUEST_URI');
            $audit->req_data = $this->request->getPost();

            $audit->create();
        }
    }

    protected function getAuthInfo()
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
