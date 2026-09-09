<?php
/**
 * @copyright Copyright (c) 2026 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Annotations\Adapter;

use Phalcon\Annotations\Adapter\AbstractAdapter;
use Phalcon\Annotations\Reflection;
use Phalcon\Storage\Adapter\Redis as RedisStorage;
use Phalcon\Storage\SerializerFactory;

class Redis extends AbstractAdapter
{
    /**
     * @var RedisStorage
     */
    protected RedisStorage $redis;

    /**
     * @var string
     */
    protected string $prefix = 'ph-annotation-';

    /**
     * @var int|null
     */
    protected ?int $lifetime = null;

    public function __construct(array $options = [])
    {
        if (!isset($options['prefix'])) {
            $options['prefix'] = $this->prefix;
        }

        $serializerFactory = new SerializerFactory();

        $this->redis = new RedisStorage($serializerFactory, $options);

        if (isset($options['lifetime']) && $options['lifetime'] > 0) {
            $this->lifetime = (int)$options['lifetime'];
        }
    }

    public function read(string $key): Reflection|false
    {
        $data = $this->redis->get($key);

        if ($data) {
            return unserialize($data);
        }

        return false;
    }

    public function write(string $key, Reflection $data): bool
    {
        $serialized = serialize($data);

        if ($this->lifetime !== null) {
            return $this->redis->set($key, $serialized, $this->lifetime);
        } else {
            return $this->redis->set($key, $serialized);
        }
    }

}
