<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Api as ApiAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
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

        return true;
    }

    public function initialize()
    {
        $this->authUser = $this->getAuthUser();

        $this->eventsManager->fire('Site:afterView', $this, $this->authUser);
    }

    protected function getAuthUser()
    {
        /**
         * @var ApiAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
