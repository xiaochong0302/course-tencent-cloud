<?php

namespace App\Http\Home\Controllers;

use App\Caches\Config as ConfigCache;
use App\Caches\NavTreeList as NavTreeListCache;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    protected $siteConfig;
    protected $navList;
    protected $authUser;

    use ResponseTrait, SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            if (!$this->checkHttpReferer() || !$this->checkCsrfToken()) {
                $dispatcher->forward([
                    'controller' => 'public',
                    'action' => 'robot',
                ]);
                return false;
            }
        }

        $this->siteConfig = $this->getSiteConfig();
        $this->navList = $this->getNavList();
        $this->authUser = $this->getAuthUser();

        return true;
    }

    public function initialize()
    {
        $this->view->setVar('auth_user', $this->authUser);
        $this->view->setVar('site_config', $this->siteConfig);
        $this->view->setVar('top_nav_list', $this->navList['top']);
        $this->view->setVar('btm_nav_list', $this->navList['bottom']);
    }

    protected function getAuthUser()
    {
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

    protected function getNavList()
    {
        $cache = new NavTreeListCache();

        return $cache->get();
    }

    protected function getSiteConfig()
    {
        $cache = new ConfigCache();

        return $cache->getSectionConfig('site');
    }

}
