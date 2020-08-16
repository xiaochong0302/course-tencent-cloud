<?php

namespace App\Http\Web\Controllers;

use App\Caches\NavTreeList as NavCache;
use App\Caches\Setting as SettingCache;
use App\Library\AppInfo;
use App\Library\Seo;
use App\Models\User as UserModel;
use App\Services\Auth\Web as WebAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
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
    protected $site;

    /**
     * @var array
     */
    protected $navs;

    /**
     * @var array
     */
    protected $appInfo;

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;
    use SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->site = $this->getSiteSettings();

        $this->checkSiteStatus();

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
        $this->authUser = $this->getAuthUser();

        $this->seo->setTitle($this->site['title']);

        $this->view->setVar('site', $this->site);
        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('navs', $this->navs);
        $this->view->setVar('app_info', $this->appInfo);
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('socket_url', $this->getSocketUrl());
    }

    protected function getAuthUser()
    {
        /**
         * @var WebAuth $auth
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

        return $cache->get() ?: [];
    }

    protected function getSiteSettings()
    {
        $cache = new SettingCache();

        return $cache->get('site') ?: [];
    }

    protected function getAppInfo()
    {
        return new AppInfo();
    }

    protected function getSocketUrl()
    {
        $config = $this->getDI()->get('config');

        return $config->websocket->url;
    }

    protected function checkSiteStatus()
    {
        if ($this->site['status'] == 'closed') {
            $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'shutdown',
                'params' => ['message' => $this->site['closed_tips']],
            ]);
        }
    }

}
