<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Http\Response\Cookies as PhCookies;

class Cookie extends Provider
{

    protected $serviceName = 'cookies';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $cookies = new PhCookies();

            $cookies->useEncryption(true);

            return $cookies;
        });
    }

}
