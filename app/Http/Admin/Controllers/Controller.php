<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Models\Audit as AuditModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
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

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeDispatchLoop(Dispatcher $dispatcher)
    {
        /*$role = 'guest';
        $auth = $this->getDI()->get('auth');
        $module     = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();

        if ($authUser = $auth->getCurrentUser()) {
            $role = $authUser->role;
        }
        $resourceKey = $module . '.' . $controller;
        $resourceVal = $action;

        $acl = $this->getDI()->get('acl');

        if ($acl->isResource($resourceKey)) {
            if (!$acl->isAllowed($role, $resourceKey, $resourceVal)) {
                $this->forbidden();
                return false;
            }
        }*/
    }

    //can be removed later;
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $auth = $this->getDI()->get('auth');
        $this->authInfo = $auth->getAuthInfo();

        if (!$this->authInfo) {
            $dispatcher->forward([
                'controller' => 'public',
                'action' => 'auth',
            ]);
            return false;
        }

        $this->authUser = $auth->getCurrentUser();

        /**
         * root用户忽略权限检查
         */
        if ($this->authUser->admin_role == RoleModel::ROLE_ROOT) {
            return true;
        }

        /**
         * 特例白名单
         */
        $whitelist = [
            'controllers' => ['public', 'index', 'upload', 'test'],
            'routes' => [],
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
        $this->view->setVar('auth_user', $this->authUser);
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isPost()) {

            $audit = new AuditModel();

            $audit->user_id = $this->authUser->id;
            $audit->user_name = $this->authUser->name;
            $audit->role    = $this->authUser->role;
            $audit->user_ip = $this->request->getClientAddress();
            $audit->req_route = $this->router->getMatchedRoute()->getName();
            $audit->req_path = $this->request->getServer('REQUEST_URI');
            $audit->req_data = $this->request->getPost();

            $audit->create();
        }
    }
}
