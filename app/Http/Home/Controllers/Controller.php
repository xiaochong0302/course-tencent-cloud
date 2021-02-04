<?php

namespace App\Http\Home\Controllers;

use App\Caches\NavTreeList as NavCache;
use App\Library\AppInfo as AppInfo;
use App\Library\Seo as Seo;
use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;
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
    protected $imInfo;

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->authUser = $this->getAuthUser();

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

        $this->checkRateLimit();

        return true;
    }

    public function initialize()
    {
        $this->seo = $this->getSeo();
        $this->navs = $this->getNavs();
        $this->appInfo = $this->getAppInfo();
        $this->imInfo = $this->getImInfo();

        /**
         * @todo 内部操作会改变afterFetch()
         */
        $this->fireSiteViewEvent($this->authUser);

        $this->seo->setTitle($this->siteInfo['title']);

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('navs', $this->navs);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('app_info', $this->appInfo);
        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('im_info', $this->imInfo);
    }

    protected function getAuthUser()
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
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

    protected function getAppInfo()
    {
        return new AppInfo();
    }

    protected function getImInfo()
    {
        $websocket = $this->getConfig()->get('websocket');

        /**
         * ssl通过nginx转发实现
         */
        if ($this->request->isSecure()) {
            $websocket->connect_url = sprintf('wss://%s/wss', $this->request->getHttpHost());
        } else {
            $websocket->connect_url = sprintf('ws://%s', $websocket->connect_address);
        }

        return [
            'main' => $this->getSettings('im.main'),
            'cs' => $this->getSettings('im.cs'),
            'ws' => $websocket,
        ];
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

    protected function fireSiteViewEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('site:view', $this, $user);
    }

}
