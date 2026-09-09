<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Cache\CacheInterface;
use Phalcon\Support\Helper\Str\Random;
use Phalcon\Support\HelperFactory;

class Verify extends Service
{

    /**
     * @var CacheInterface
     */
    protected CacheInterface $cache;

    public function __construct()
    {
        $this->cache = $this->getCache();
    }

    public function getSmsCode(string $phone, int $lifetime = 300): string
    {
        $key = $this->getSmsCacheKey($phone);

        $HelperFactory = new HelperFactory();

        $code = $HelperFactory->random(Random::RANDOM_NUMERIC, 6);

        $this->cache->set($key, $code, $lifetime);

        return $code;
    }

    public function getMailCode(string $email, int $lifetime = 300): string
    {
        $key = $this->getMailCacheKey($email);

        $HelperFactory = new HelperFactory();

        $code = $HelperFactory->random(Random::RANDOM_NUMERIC, 6);

        $this->cache->set($key, $code, $lifetime);

        return $code;
    }

    public function checkSmsCode(string $phone, string $code): bool
    {
        $key = $this->getSmsCacheKey($phone);

        $value = $this->cache->get($key);

        if (empty($value)) return false;

        return $code == $value;
    }

    public function checkMailCode(string $email, string $code): bool
    {
        $key = $this->getMailCacheKey($email);

        $value = $this->cache->get($key);

        if (empty($value)) return false;

        return $code == $value;
    }

    protected function getMailCacheKey(string $email): string
    {
        return sprintf('verify-mail-%s', crc32($email));
    }

    protected function getSmsCacheKey(string $phone): string
    {
        return sprintf('verify-sms-%s', crc32($phone));
    }

}
