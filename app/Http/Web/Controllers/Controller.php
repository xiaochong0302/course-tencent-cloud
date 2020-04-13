<?php

namespace App\Http\Web\Controllers;

use App\Caches\NavTreeList as NavTreeListCache;
use App\Caches\Setting as SettingCache;
use App\Library\Seo as SiteSeo;
use App\Services\Auth\Web as WebAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;
use Yansongda\Supports\Collection;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var SiteSeo
     */
    protected $seo;

    /**
     * @var Collection
     */
    protected $site;

    /**
     * @var Collection
     */
    protected $nav;

    /**
     * @var Collection
     */
    protected $authUser;

    use ResponseTrait, SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkRateLimit();

        $this->seo = $this->getSiteSeo();
        $this->site = $this->getSiteSettings();
        $this->nav = $this->getNavList();
        $this->authUser = $this->getAuthUser();

        return true;
    }

    public function initialize()
    {
        $this->seo->setTitle($this->site->title);

        $this->view->setVar('seo', $this->seo);
        $this->view->setVar('site', $this->site);
        $this->view->setVar('nav', $this->nav);
        $this->view->setVar('auth_user', $this->authUser);
    }

    protected function getAuthUser()
    {
        /**
         * @var WebAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

    protected function getNavList()
    {
        $cache = new NavTreeListCache();

        return $cache->get();
    }

    protected function getSiteSettings()
    {
        $cache = new SettingCache();

        return $cache->get('site');
    }

    protected function getSiteSeo()
    {
        return new SiteSeo();
    }

}
