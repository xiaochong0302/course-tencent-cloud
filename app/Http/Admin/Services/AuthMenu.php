<?php

namespace App\Http\Admin\Services;

use App\Services\Auth\Admin as AdminAuth;
use Phalcon\Mvc\User\Component;

class AuthMenu extends Component
{

    protected $authUser;
    protected $authNodes = [];
    protected $ownedRoutes = [];
    protected $owned1stLevelIds = [];
    protected $owned2ndLevelIds = [];
    protected $owned3rdLevelIds = [];

    public function __construct()
    {
        $this->authUser = $this->getAuthInfo();
        $this->authNodes = $this->getAuthNodes();
        $this->setOwnedLevelIds();
    }

    public function getTopMenus()
    {
        $menus = [];

        foreach ($this->authNodes as $node) {
            if ($this->authUser->id || in_array($node['id'], $this->owned1stLevelIds)) {
                $menus[] = [
                    'id' => $node['id'],
                    'title' => $node['title'],
                ];
            }
        }

        return $menus;
    }

    public function getLeftMenus()
    {
        $menus = [];

        foreach ($this->authNodes as $key => $level) {
            foreach ($level['children'] as $key2 => $level2) {
                foreach ($level2['children'] as $key3 => $level3) {
                    $hasRight = $this->authUser->root || in_array($level3['id'], $this->owned3rdLevelIds);
                    if ($level3['type'] == 'menu' && $hasRight) {
                        $menus[$key]['id'] = $level['id'];
                        $menus[$key]['title'] = $level['title'];
                        $menus[$key]['children'][$key2]['id'] = $level2['id'];
                        $menus[$key]['children'][$key2]['title'] = $level2['title'];
                        $menus[$key]['children'][$key2]['children'][$key3] = [
                            'id' => $level3['id'],
                            'title' => $level3['title'],
                            'url' => $this->url->get(['for' => $level3['route']]),
                        ];
                    }
                }
            }
        }

        return $menus;
    }

    protected function setOwnedLevelIds()
    {
        $routeIdMapping = $this->getRouteIdMapping();

        if (!$routeIdMapping) return;

        $owned1stLevelIds = [];
        $owned2ndLevelIds = [];
        $owned3rdLevelIds = [];

        foreach ($routeIdMapping as $key => $value) {
            $ids = explode('-', $value);
            if (in_array($key, $this->authUser->routes)) {
                $owned1stLevelIds[] = $ids[0];
                $owned2ndLevelIds[] = $ids[0] . '-' . $ids[1];
                $owned3rdLevelIds[] = $value;
            }
        }

        $this->owned1stLevelIds = array_unique($owned1stLevelIds);
        $this->owned2ndLevelIds = array_unique($owned2ndLevelIds);
        $this->owned3rdLevelIds = array_unique($owned3rdLevelIds);
    }

    protected function getRouteIdMapping()
    {
        $mapping = [];

        foreach ($this->authNodes as $level) {
            foreach ($level['children'] as $level2) {
                foreach ($level2['children'] as $level3) {
                    if ($level3['type'] == 'menu') {
                        $mapping[$level3['route']] = $level3['id'];
                    }
                }
            }
        }

        return $mapping;
    }

    protected function getAuthNodes()
    {
        $authNode = new AuthNode();

        return $authNode->getNodes();
    }

    protected function getAuthInfo()
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
