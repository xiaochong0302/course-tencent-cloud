<?php

namespace App\Http\Desktop\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Desktop as DesktopAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class LayerController extends \Phalcon\Mvc\Controller
{

    /**
     * @var UserModel
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

        return true;
    }

    public function initialize()
    {
        $this->authUser = $this->getAuthUser();

        $this->view->setVar('auth_user', $this->authUser);
    }

    protected function getAuthUser()
    {
        /**
         * @var DesktopAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
