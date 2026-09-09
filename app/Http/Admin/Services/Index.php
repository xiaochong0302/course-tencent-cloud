<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\SiteGlobalStat as SiteGlobalStatCache;
use App\Caches\SiteTodayStat as SiteTodayStatCache;
use App\Library\AppInfo as AppInfo;
use App\Library\Utils\ServerInfo as ServerInfo;
use GuzzleHttp\Client as HttpClient;

class Index extends Service
{

    public function getTopMenus(): array
    {
        $authMenu = new AuthMenu();

        return $authMenu->getTopMenus();
    }

    public function getLeftMenus(): array
    {
        $authMenu = new AuthMenu();

        return $authMenu->getLeftMenus();
    }

    public function getAppInfo(): AppInfo
    {
        return new AppInfo();
    }

    public function getSiteInfo(): array
    {
        return $this->getSettings('site');
    }

    public function getServerInfo(): array
    {
        return [
            'cpu' => ServerInfo::cpu(),
            'memory' => ServerInfo::memory(),
            'disk' => ServerInfo::disk(),
        ];
    }

    public function getGlobalStat(): array
    {
        $cache = new SiteGlobalStatCache();

        return $cache->get();
    }

    public function getTodayStat(): array
    {
        $cache = new SiteTodayStatCache();

        return $cache->get();
    }

    public function getReleases(): array
    {
        $cache = $this->getCache();

        $cacheKey = '_RELEASES_';

        $content = $cache->get($cacheKey);

        if ($content) return $content;

        $url = 'https://www.koogua.com/api/releases';

        $client = new HttpClient();

        $response = $client->get($url);

        $content = json_decode($response->getBody()->getContents(), true);

        $releases = $content['releases'] ?? [];

        $cache->set($cacheKey, $releases, 86400);

        return $releases;
    }

}
