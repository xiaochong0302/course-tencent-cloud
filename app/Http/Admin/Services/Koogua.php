<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\AppInfo as AppInfo;
use GuzzleHttp\Client as HttpClient;

class Koogua extends Service
{

    public function syncAppInfo(): void
    {
        $apiUrl = 'https://www.koogua.com/api/instance/collect';

        $site = $this->getSettings('site');

        $serverHost = parse_url($site['url'], PHP_URL_HOST);

        $serverIp = gethostbyname($serverHost);

        $appInfo = new AppInfo();

        $params = [
            'server_host' => $serverHost,
            'server_ip' => $serverIp,
            'app_name' => $appInfo->get('name'),
            'app_alias' => $appInfo->get('alias'),
            'app_version' => $appInfo->get('version'),
            'app_link' => $appInfo->get('link'),
        ];

        $client = new HttpClient();

        $client->request('POST', $apiUrl, ['form_params' => $params]);
    }

}
