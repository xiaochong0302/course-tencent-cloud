<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Http\Response\Cookies as PhCookies;

class Cookie extends Provider
{

    protected string $serviceName = 'cookies';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $cookies = new PhCookies();

            $cookies->useEncryption(true);
            $cookies->setSignKey($config->get('key'));

            return $cookies;
        });
    }

}
