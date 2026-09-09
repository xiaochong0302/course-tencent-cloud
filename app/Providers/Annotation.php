<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Library\Annotations\Adapter\Redis as RedisAdapter;
use Phalcon\Annotations\Adapter\Memory as MemoryAdapter;
use Phalcon\Config\Config;

class Annotation extends Provider
{

    protected string $serviceName = 'annotations';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $annotations = new MemoryAdapter();

            } else {

                $options = [
                    'defaultSerializer' => 'redis_igbinary',
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('redis.index'),
                    'prefix' => $config->path('annotation.prefix'),
                    'lifetime' => $config->path('annotation.lifetime'),
                ];

                $annotations = new RedisAdapter($options);
            }

            return $annotations;
        });
    }

}
