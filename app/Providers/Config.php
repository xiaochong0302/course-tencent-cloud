<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Config as PhConfig;

class Config extends Provider
{

    protected $serviceName = 'config';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $options = require config_path('config.php');

            return new PhConfig($options);
        });
    }

}
