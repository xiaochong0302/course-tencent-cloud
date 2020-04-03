<?php

namespace App\Library;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Di;
use Phalcon\Session\Adapter\Redis as RedisSession;
use Phalcon\Text;

class Security
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var RedisSession
     */
    protected $session;

    protected $options = [];

    protected $tokenKey;

    protected $tokenValue;

    public function __construct($options = [])
    {
        $this->options['lifetime'] = $options['lifetime'] ?? 3600;

        $this->cache = Di::getDefault()->get('cache');

        $this->session = Di::getDefault()->get('session');

        $this->generateToken();
    }

    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    public function getTokenValue()
    {
        return $this->tokenValue;
    }

    public function generateToken()
    {
        $this->tokenKey = $this->session->getId();

        $key = $this->getCacheKey($this->tokenKey);

        $content = [
            'hash' => Text::random(Text::RANDOM_ALNUM, 32),
            'time' => time(),
        ];

        $lifetime = $this->options['lifetime'];

        $cache = $this->cache->get($key);

        if ($cache) {
            $this->tokenValue = $cache['hash'];
            if (time() - $cache['time'] > $lifetime / 2) {
                $this->cache->save($key, $content, $lifetime);
                $this->tokenValue = $content['hash'];
            }
        } else {
            $this->cache->save($key, $content, $lifetime);
            $this->tokenValue = $content['hash'];
        }
    }

    public function checkToken($tokenKey, $tokenValue)
    {
        $key = $this->getCacheKey($tokenKey);

        $content = $this->cache->get($key);

        if (!$content) {
            return false;
        }

        return $tokenValue == $content['hash'];
    }

    protected function getCacheKey($tokenKey)
    {
        return "csrf_token:{$tokenKey}";
    }

}
