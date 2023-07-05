<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;
use Phalcon\Mvc\Model\MetaData\Redis as RedisMetaData;

class MetaData extends Provider
{

    protected $serviceName = 'modelsMetadata';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $metaData = new MemoryMetaData();

            } else {

                $statsKey = '_METADATA_';

                $metaData = new RedisMetaData([
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('redis.index'),
                    'lifetime' => $config->path('metadata.lifetime'),
                    'prefix' => $statsKey . ':',
                    'statsKey' => $statsKey,
                ]);
            }

            return $metaData;
        });
    }

}
