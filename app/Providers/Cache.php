<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use App\Library\Cache\Backend\Redis as RedisBackend;
use Phalcon\Cache\Frontend\Igbinary as IgbinaryFrontend;
use Phalcon\Config;

class Cache extends Provider
{

    protected $serviceName = 'cache';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $frontend = new IgbinaryFrontend([
                'lifetime' => $config->path('cache.lifetime'),
            ]);

            return new RedisBackend($frontend, [
                'host' => $config->path('redis.host'),
                'port' => $config->path('redis.port'),
                'auth' => $config->path('redis.auth'),
                'index' => $config->path('redis.index'),
            ]);
        });
    }

}
