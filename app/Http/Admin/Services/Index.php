<?php

namespace App\Http\Admin\Services;

use App\Caches\SiteStat;
use App\Library\AppInfo;
use App\Library\Utils\ServerInfo;

class Index extends Service
{

    public function getTopMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getTopMenus();
    }

    public function getLeftMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getLeftMenus();
    }

    public function getAppInfo()
    {
        return new AppInfo();
    }

    public function getServerInfo()
    {
        return [
            'cpu' => ServerInfo::cpu(),
            'memory' => ServerInfo::memory(),
            'disk' => ServerInfo::disk(),
        ];
    }

    public function getStatInfo()
    {
        $cache = new SiteStat();

        return $cache->get();
    }

}
