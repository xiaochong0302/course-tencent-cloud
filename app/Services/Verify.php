<?php

namespace App\Services;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Text;

class Verify extends Service
{

    /**
     * @var RedisCache
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getCache();
    }

    public function getSmsCode($phone, $lifetime = 300)
    {
        $key = $this->getSmsCacheKey($phone);

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $this->cache->save($key, $code, $lifetime);

        return $code;
    }

    public function getEmailCode($email, $lifetime = 300)
    {
        $key = $this->getEmailCacheKey($email);

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $this->cache->save($key, $code, $lifetime);

        return $code;
    }

    public function checkSmsCode($phone, $code)
    {
        $key = $this->getSmsCacheKey($phone);

        $value = $this->cache->get($key);

        return $code == $value;
    }

    public function checkEmailCode($email, $code)
    {
        $key = $this->getEmailCacheKey($email);

        $value = $this->cache->get($key);

        return $code == $value;
    }

    protected function getEmailCacheKey($email)
    {
        return "verify:email:{$email}";
    }

    protected function getSmsCacheKey($phone)
    {
        return "verify:sms:{$phone}";
    }

}
