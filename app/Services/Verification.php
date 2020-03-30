<?php

namespace App\Services;

use App\Services\Mailer\Verify as VerifyMailer;
use App\Services\Smser\Verify as VerifySmser;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Text;

class Verification extends Service
{

    /**
     * @var Redis
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');
    }

    public function sendSmsCode($phone)
    {
        $smser = new VerifySmser();

        $smser->handle($phone);
    }

    public function sendMailCode($email)
    {
        $mailer = new VerifyMailer();

        $mailer->handle($email);
    }

    public function getSmsCode($phone, $lifetime = 300)
    {
        $key = $this->getSmsCacheKey($phone);

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $this->cache->save($key, $code, $lifetime);
    }

    public function getMailCode($email, $lifetime = 300)
    {
        $key = $this->getSmsCacheKey($email);

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $this->cache->save($key, $code, $lifetime);
    }

    public function checkSmsCode($phone, $code)
    {
        $key = $this->getSmsCacheKey($phone);

        $value = $this->cache->get($key);

        return $code == $value;
    }

    public function checkMailCode($email, $code)
    {
        $key = $this->getMailCacheKey($email);

        $value = $this->cache->get($key);

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
