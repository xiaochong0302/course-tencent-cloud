<?php

namespace App\Http\Admin\Services;

use Phalcon\Mvc\User\Component;

class AuthMenu extends Component

{

    protected $authNodes = [];
    protected $ownedRoutes = [];
    protected $owned1stLevelIds = [];
    protected $owned2ndLevelIds = [];
    protected $owned3rdLevelIds = [];
    protected $authUser;

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
            if ($this->authUser->root || in_array($node['id'], $this->owned1stLevelIds)) {
                $menus[] = [
                    'id' => $node['id'],
                    'label' => $node['label'],
                ];
            }
        }

        return $menus;
    }

    public function getLeftMenus()
    {
        $menus = [];

        foreach ($this->authNodes as $key => $level) {
            foreach ($level['child'] as $key2 => $level2) {
                foreach ($level2['child'] as $key3 => $level3) {
                    $hasRight = $this->authUser->root || in_array($level3['id'], $this->owned3rdLevelIds);
                    if ($level3['type'] == 'menu' && $hasRight) {
                        $menus[$key]['id'] = $level['id'];
                        $menus[$key]['label'] = $level['label'];
                        $menus[$key]['child'][$key2]['id'] = $level2['id'];
                        $menus[$key]['child'][$key2]['label'] = $level2['label'];
                        $menus[$key]['child'][$key2]['child'][$key3] = [
                            'id' => $level3['id'],
                            'label' => $level3['label'],
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
            foreach ($level['child'] as $level2) {
                foreach ($level2['child'] as $level3) {
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
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
