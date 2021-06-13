<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Crypt as PhCrypt;

class Crypt extends Provider
{

    protected $serviceName = 'crypt';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $crypt = new PhCrypt();

            $crypt->setKey($config->get('key'));

            return $crypt;
        });
    }

}
