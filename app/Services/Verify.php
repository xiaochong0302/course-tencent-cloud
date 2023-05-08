<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

    public function getMailCode($email, $lifetime = 300)
    {
        $key = $this->getMailCacheKey($email);

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $this->cache->save($key, $code, $lifetime);

        return $code;
    }

    public function checkSmsCode($phone, $code)
    {
        $key = $this->getSmsCacheKey($phone);

        $value = $this->cache->get($key);

        if (empty($value)) return false;

        return $code == $value;
    }

    public function checkMailCode($email, $code)
    {
        $key = $this->getMailCacheKey($email);

        $value = $this->cache->get($key);

        if (empty($value)) return false;

        return $code == $value;
    }

    protected function getMailCacheKey($email)
    {
        return "verify:mail:{$email}";
    }

    protected function getSmsCacheKey($phone)
    {
        return "verify:sms:{$phone}";
    }

}
