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
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkRateLimit();

        $this->authUser = $this->getAuthUser();

        if (!$this->authUser) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'auth',
            ]);
            return false;
        }

        $controller = $dispatcher->getControllerName();

        $route = $this->router->getMatchedRoute();

        /**
         * 管理员忽略权限检查
         */
        if ($this->authUser['root'] == 1) {
            return true;
        }

        /**
         * 特例白名单
         */
        $whitelist = [
            'controllers' => ['public', 'index', 'storage', 'vod', 'test', 'xm_course'],
            'routes' => ['admin.package.guiding'],
        ];

        /**
         * 特定控制器忽略权限检查
         */
        if (in_array($controller, $whitelist['controllers'])) {
            return true;
        }

        /**
         * 特定路由忽略权限检查
         */
        if (in_array($route->getName(), $whitelist['routes'])) {
            return true;
        }

        /**
         * 执行路由权限检查
         */
        if (!in_array($route->getName(), $this->authUser['routes'])) {
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
        $this->view->setVar('auth_user', $this->authUser);
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isPost()) {

            $audit = new AuditModel();

            $audit->user_id = $this->authUser['id'];
            $audit->user_name = $this->authUser['name'];
            $audit->user_ip = $this->request->getClientAddress();
            $audit->req_route = $this->router->getMatchedRoute()->getName();
            $audit->req_path = $this->request->getServer('REQUEST_URI');
            $audit->req_data = $this->request->getPost();

            $audit->create();
        }
    }

    protected function getAuthUser()
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
