<?php

namespace App\Library\Cache\Backend;

use Phalcon\Cache\Exception;

class Redis extends \Phalcon\Cache\Backend\Redis
{

    /**
     * @var \Redis
     */
    protected $_redis;

    /**
     * {@inheritdoc}
     *
     * @param  string $keyName
     * @param  integer $lifetime
     * @return mixed|null
     */
    public function get($keyName, $lifetime = null)
    {
        $redis = $this->getRedis();

        /**
         * @var \Phalcon\Cache\FrontendInterface $frontend
         */
        $frontend = $this->_frontend;

        $lastKey = $this->getKeyName($keyName);

        $this->_lastKey = $lastKey;

        $content = $redis->get($lastKey);

        if ($content === false) {
            return null;
        }

        if (is_numeric($content)) {
            return $content;
        }

        return $frontend->afterRetrieve($content);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $keyName
     * @param  string $content
     * @param  int $lifetime
     * @param  bool $stopBuffer
     * @return bool
     *
     * @throws Exception
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = true)
    {
        if ($keyName === null) {
            $lastKey = $this->_lastKey;
        } else {
            $lastKey = $this->getKeyName($keyName);
            $this->_lastKey = $lastKey;
        }

        if (!$lastKey) {
            throw new Exception('The cache must be started first');
        }

        $redis = $this->getRedis();

        /**
         * @var \Phalcon\Cache\FrontendInterface $frontend
         */
        $frontend = $this->_frontend;

        if ($content === null) {
            $cachedContent = $frontend->getContent();
        } else {
            $cachedContent = $content;
        }

        /**
         * Prepare the content in the frontend
         */
        if (!is_numeric($cachedContent)) {
            $preparedContent = $frontend->beforeStore($cachedContent);
        } else {
            $preparedContent = $cachedContent;
        }

        if ($lifetime === null) {
            $tmp = $this->_lastLifetime;
            $ttl = $tmp ? $tmp : $frontend->getLifetime();
        } else {
            $ttl = $lifetime;
        }

        $success = $redis->set($lastKey, $preparedContent);

        if (!$success) {
            throw new Exception('Failed storing the data in redis');
        }

        if ($ttl > 0) {
            $redis->setTimeout($lastKey, $ttl);
        }

        $isBuffering = $frontend->isBuffering();

        if ($stopBuffer === true) {
            $frontend->stop();
        }

        if ($isBuffering === true) {
            echo $cachedContent;
        }

        $this->_started = false;

        return $success;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $keyName
     * @return bool
     */
    public function delete($keyName)
    {
        $redis = $this->getRedis();

        $lastKey = $this->getKeyName($keyName);

        return (bool)$redis->delete($lastKey);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $prefix
     * @return array
     */
    public function queryKeys($prefix = null)
    {
        $redis = $this->getRedis();

        $pattern = "{$this->_prefix}" . ($prefix ? $prefix : '') . '*';

        return $redis->keys($pattern);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $keyName
     * @param  string $lifetime
     * @return bool
     */
    public function exists($keyName = null, $lifetime = null)
    {
        $redis = $this->getRedis();

        if ($keyName === null) {
            $lastKey = $this->_lastKey;
        } else {
            $lastKey = $this->getKeyName($keyName);
        }

        return (bool)$redis->exists($lastKey);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     * @param int $value
     * @return int
     */
    public function increment($keyName = null, $value = 1)
    {
        $redis = $this->getRedis();

        if ($keyName === null) {
            $lastKey = $this->_lastKey;
        } else {
            $lastKey = $this->getKeyName($keyName);
        }

        return $redis->incrBy($lastKey, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $keyName
     * @param int $value
     * @return int
     */
    public function decrement($keyName = null, $value = 1)
    {
        $redis = $this->getRedis();

        if ($keyName === null) {
            $lastKey = $this->_lastKey;
        } else {
            $lastKey = $this->getKeyName($keyName);
        }

        return $redis->decrBy($lastKey, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function flush()
    {

    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Get redis connection
     *
     * @return \Redis
     */
    public function getRedis()
    {
        $redis = $this->_redis;

        if (!is_object($redis)) {
            $this->_connect();
            $redis = $this->_redis;
        }

        return $redis;
    }

    /**
     * Get key name
     *
     * @param $keyName
     * @return string
     */
    public function getKeyName($keyName)
    {
        return $this->_prefix . $keyName;
    }

    /**
     * Get raw key name
     *
     * @param $keyName
     * @return string
     */
    public function getRawKeyName($keyName)
    {
        if ($this->_prefix) {
            $keyName = str_replace($this->_prefix, '', $keyName);
        }

        return $keyName;
    }

}
