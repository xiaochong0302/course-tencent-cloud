<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Encryption\Crypt as PhCrypt;

class Crypt extends Provider
{

    protected string $serviceName = 'crypt';

    public function register(): void
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
