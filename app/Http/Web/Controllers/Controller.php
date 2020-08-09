<?php

namespace App\Http\Web\Controllers;

use App\Caches\NavTreeList as NavTreeListCache;
use App\Caches\Setting as SettingCache;
use App\Library\Seo as Seo;
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
    protected $settings;

    /**
     * @var array
     */
    protected $navs;

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
        $this->seo = $this->getSeo();
        $this->navs = $this->getNavs();
        $this->settings = $this->getSettings();
        $this->authUser = $this->getAuthUser();

        $this->seo->setTitle($this->settings['title']);

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('navs', $this->navs);
        $this->view->setVar('settings', $this->settings);
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

    protected function getNavs()
    {
        $cache = new NavTreeListCache();

        return $cache->get() ?: [];
    }

    protected function getSeo()
    {
        return new Seo();
    }

    protected function getSettings()
    {
        $cache = new SettingCache();

        return $cache->get('site') ?: [];
    }

    protected function getSocketUrl()
    {
        $config = $this->getDI()->get('config');

        return $config->websocket->url;
    }

}
