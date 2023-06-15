<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Annotations\Adapter\Memory as MemoryAnnotations;
use Phalcon\Annotations\Adapter\Redis as RedisAnnotations;
use Phalcon\Config;

class Annotation extends Provider
{

    protected $serviceName = 'annotations';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $annotations = new MemoryAnnotations();

            } else {

                $statsKey = '_ANNOTATION_';

                $annotations = new RedisAnnotations([
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('redis.index'),
                    'lifetime' => $config->path('annotation.lifetime'),
                    'prefix' => $statsKey . ':',
                    'statsKey' => $statsKey,
                ]);
            }

            return $annotations;
        });
    }

}
