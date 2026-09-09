<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Mvc\Url as PhUrl;

class Url extends Provider
{

    protected string $serviceName = 'url';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $url = new PhUrl();

            $url->setBaseUri($config->get('base_uri'));
            $url->setStaticBaseUri($config->get('static_base_uri'));

            return $url;
        });
    }

}
