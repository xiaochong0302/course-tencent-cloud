<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Caches\NavTreeList as NavCache;
use App\Library\AppInfo as AppInfo;
use App\Library\Seo as Seo;
use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Config;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var Seo
     */
    protected $seo;

    /**
     * @var array
     */
    protected $navs;

    /**
     * @var array
     */
    protected $appInfo;

    /**
     * @var array
     */
    protected $siteInfo;

    /**
     * @var array
     */
    protected $contactInfo;

    /**
     * @var Config
     */
    protected $websocketInfo;

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->authUser = $this->getAuthUser(true);

        if ($this->siteInfo['status'] == 'closed') {
            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'maintain',
            ]);
            return false;
        }

        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        return true;
    }

    public function initialize()
    {
        $this->eventsManager->fire('Site:afterView', $this, $this->authUser);

        $this->seo = $this->getSeo();
        $this->navs = $this->getNavs();
        $this->appInfo = $this->getAppInfo();
        $this->contactInfo = $this->getContactInfo();
        $this->websocketInfo = $this->getWebsocketInfo();

        $this->seo->setTitle($this->siteInfo['title']);

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('navs', $this->navs);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('app_info', $this->appInfo);
        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('contact_info', $this->contactInfo);
        $this->view->setVar('websocket_info', $this->websocketInfo);
    }

    protected function getAuthUser($cache = true)
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser($cache);
    }

    protected function getSeo()
    {
        return new Seo();
    }

    protected function getNavs()
    {
        $cache = new NavCache();

        return $cache->get();
    }

    protected function getSiteInfo()
    {
        return $this->getSettings('site');
    }

    protected function getContactInfo()
    {
        return $this->getSettings('contact');
    }

    protected function getAppInfo()
    {
        return new AppInfo();
    }

    protected function getWebsocketInfo()
    {
        $websocket = $this->getConfig()->get('websocket');

        /**
         * ssl通过nginx转发实现
         */
        if ($this->request->isSecure()) {
            list($domain) = explode(':', $websocket->connect_address);
            $websocket->connect_url = sprintf('wss://%s/wss', $domain);
        } else {
            $websocket->connect_url = sprintf('ws://%s', $websocket->connect_address);
        }

        return $websocket;
    }

    protected function getConfig()
    {
        $appService = new AppService();

        return $appService->getConfig();
    }

    protected function getSettings($section)
    {
        $appService = new AppService();

        return $appService->getSettings($section);
    }

}
