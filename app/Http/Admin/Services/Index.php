<?php

namespace App\Http\Admin\Services;

use App\Caches\SiteGlobalStat;
use App\Caches\SiteTodayStat;
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

    public function getGlobalStat()
    {
        $cache = new SiteGlobalStat();

        return $cache->get();
    }

    public function getTodayStat()
    {
        $cache = new SiteTodayStat();

        return $cache->get();
    }

}
