<?php

namespace App\Http\Admin\Services;

use App\Caches\SiteGlobalStat;
use App\Caches\SiteTodayStat;
use App\Library\AppInfo;
use App\Library\Utils\ServerInfo;
use GuzzleHttp\Client;

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

    public function getReleases()
    {
        $url = 'https://koogua.com/api-releases.json';

        $client = new Client();

        $response = $client->get($url, ['timeout' => 3]);

        $content = json_decode($response->getBody(), true);

        return $content['releases'] ?? [];
    }

}
