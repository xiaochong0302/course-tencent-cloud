<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use App\Library\Logger as AppLogger;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;
use Phalcon\Logger\Logger;

class Listener extends Injectable
{

    use ServiceTrait;

    public function getLogger(string $channel = 'listen'): Logger
    {
        $logger = new AppLogger();

        return $logger->getInstance($channel);
    }

}
