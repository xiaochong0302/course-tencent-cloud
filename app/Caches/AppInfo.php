<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

class AppInfo extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 365 * 86400;

    public function getKey($id = null): string
    {
        return '_APP_INFO_';
    }

    public function getContent($id = null): array
    {
        $appInfo = new \App\Library\AppInfo();

        return [
            'name' => $appInfo->get('name'),
            'alias' => $appInfo->get('alias'),
            'link' => $appInfo->get('link'),
            'version' => $appInfo->get('version'),
        ];
    }

}
