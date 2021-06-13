<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Services\Service as AppService;


class Task extends \Phalcon\Cli\Task
{

    public function getConfig()
    {
        $appService = new AppService();

        return $appService->getConfig();
    }


    public function getCache()
    {
        $appService = new AppService();

        return $appService->getCache();
    }

    public function getRedis()
    {
        $appService = new AppService();

        return $appService->getRedis();
    }

    public function getLogger($channel = 'console')
    {
        $appService = new AppService();

        return $appService->getLogger($channel);
    }

    public function getSettings($section)
    {
        $appService = new AppService();

        return $appService->getSettings($section);
    }

}
