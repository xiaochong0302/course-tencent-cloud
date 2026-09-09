<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config as PhConfig;

class Config extends Provider
{

    protected string $serviceName = 'config';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {

            $options = require config_path('config.php');

            return new PhConfig($options);
        });
    }

}
