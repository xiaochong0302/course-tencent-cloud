<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Session\Adapter\Files;
use Phalcon\Session\Adapter\Libmemcached;
//use Phalcon\Session\Adapter\Redis;
use App\Library\Session\Adapter\Redis;

class Session extends Provider
{

    protected $serviceName = 'session';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {
            $session = null;
            $session_handler = $config->path('session.handler')?$config->path('session.handler'):'file';
            switch ($session_handler) {
                case 'file':
                    $session = new Files();
                    break;
                case 'memcached':
                    $session = new Libmemcached(
                        [
                            "servers" => [
                                $config->memcached->toArray(),
                            ],
                            "client" => [
                                \Memcached::OPT_HASH       => \Memcached::HASH_MD5,
                                \Memcached::OPT_PREFIX_KEY => "prefix.",
                            ],
                            "lifetime" => 3600,
                            'prefix' => '_SESSION_:',
                        ]
                    );
                    break;
                case 'redis':
                    $session = new Redis([
                        'host' => $config->path('redis.host'),
                        'port' => $config->path('redis.port'),
                        'auth' => $config->path('redis.auth'),
                        'index' => $config->path('redis.index') ?: 0,
                        'lifetime' => $config->path('session.lifetime') ?: 24 * 3600,
                        'prefix' => '_SESSION_:',
                    ]);
                    break;
                default:
                    $session = new Files;
            }

            $session->start();

            return $session;
        });
    }

}
