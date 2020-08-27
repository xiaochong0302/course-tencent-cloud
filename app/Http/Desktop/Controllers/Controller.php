<?php

namespace App\Http\Desktop\Controllers;

use App\Caches\NavTreeList as NavCache;
use App\Caches\Setting as SettingCache;
use App\Library\AppInfo;
use App\Library\Seo;
use App\Models\User as UserModel;
use App\Services\Auth\Desktop as DesktopAuth;
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
        $this->authUser = $this->getAuthUser();
        $this->appInfo = $this->getAppInfo();
        $this->imInfo = $this->getImInfo();

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
         * @var DesktopAuth $auth
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
        $cache = new SettingCache();

        return $cache->get('site');
    }

    protected function getAppInfo()
    {
        return new AppInfo();
    }

    protected function getImInfo()
    {
        $cache = new SettingCache();

        return [
            'main' => $cache->get('im.main'),
            'cs' => $cache->get('im.cs'),
            'websocket' => $this->config->websocket,
        ];
    }

    protected function checkSiteStatus()
    {
        if ($this->siteInfo['enabled'] == 0) {
            $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'maintain',
                'params' => ['message' => $this->siteInfo['closed_tips']],
            ]);
        }
    }

}
