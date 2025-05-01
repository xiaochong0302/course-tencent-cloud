<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class LayerController extends \Phalcon\Mvc\Controller
{

    /**
     * @var array
     */
    protected $siteInfo;

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

        return true;
    }

    public function initialize()
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->authUser = $this->getAuthUser();

        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('auth_user', $this->authUser);
    }

    protected function getSiteInfo()
    {
        $appService = new AppService();

        return $appService->getSettings('site');
    }

    protected function getAuthUser()
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
