<?php

namespace App\Http\Api\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Api as ApiAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->getHeader('Origin')) {
            $this->setCors();
        }

        $this->checkRateLimit();

        return true;
    }

    public function initialize()
    {
        $this->authUser = $this->getAuthUser();

        $this->fireSiteViewEvent($this->authUser);
    }

    protected function getAuthUser()
    {
        /**
         * @var ApiAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

    protected function fireSiteViewEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('site:view', $this, $user);
    }

}
