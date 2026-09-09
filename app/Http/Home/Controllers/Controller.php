<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Common as CommonService;
use App\Library\Seo as Seo;
use App\Models\User as UserModel;
use App\Traits\Auth as AuthTrait;
use App\Traits\Client as ClientTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;
    use ClientTrait;
    use AuthTrait;

    /**
     * @var Seo
     */
    protected Seo $seo;

    /**
     * @var array
     */
    protected array $siteInfo;

    /**
     * @var UserModel
     */
    protected UserModel $authUser;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $commonService = new CommonService();

        $this->siteInfo = $commonService->getSiteInfo();

        if ($this->siteInfo['status'] == 'closed') {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'maintain',
            ]);
            return false;
        }

        $this->authUser = $this->getCurrentUser(true);

        if ($this->siteInfo['private'] == 1 && $this->authUser->id == 0) {

            $whitelist = [
                'controllers' => ['public', 'account'],
                'routes' => [],
            ];

            $controllerName = $dispatcher->getControllerName();

            if (in_array($controllerName, $whitelist['controllers'])) {
                return true;
            }

            $routeName = $this->router->getMatchedRoute();

            if (in_array($routeName, $whitelist['routes'])) {
                return true;
            }

            $dispatcher->forward([
                'controller' => 'account',
                'action' => 'login',
            ]);

            return false;
        }

        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkClientAddress();
        $this->checkRateLimit();

        return true;
    }

    public function initialize()
    {
        $this->eventsManager->fire('Site:afterView', $this, $this->authUser);

        $commonService = new CommonService();

        $navs = $commonService->getNavs();
        $appInfo = $commonService->getAppInfo();
        $contactInfo = $commonService->getContactInfo();

        $this->seo = $commonService->getSeo();
        $this->seo->title = $this->siteInfo['title'];

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('navs', $navs);
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('contact_info', $contactInfo);
    }

}
