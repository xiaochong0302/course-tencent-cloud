<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Caches\NavTreeList as NavCache;
use App\Http\Api\Services\Setting as ApiSettingService;
use App\Library\AppInfo as AppInfo;
use App\Library\Seo as Seo;
use App\Services\Auth\Home as HomeAuth;

class Common extends Service
{

    public function getAppInfo(): AppInfo
    {
        return new AppInfo();
    }

    public function getSeo(): Seo
    {
        return new Seo();
    }

    public function getNavs(): array
    {
        $cache = new NavCache();

        return $cache->get();
    }

    public function getAuthInfo(): ?array
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

    public function getSiteInfo(): array
    {
        $service = new ApiSettingService();

        return $service->getSiteSettings();
    }

    public function getContactInfo(): array
    {
        $service = new ApiSettingService();

        return $service->getContactSettings();
    }

}
