<?php

namespace App\Services;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Services\Mailer\Verify as VerifyMailer;
use App\Services\Smser\Verify as VerifySmser;
use App\Validators\Verify as VerifyValidator;
use Phalcon\Text;

class Verification extends Service
{

    /**
     * @var RedisCache
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');
    }

    public function sendSmsCode($phone)
    {
        $validator = new VerifyValidator();

        $validator->checkPhone($phone);

        $smser = new VerifySmser();

        return $smser->handle($phone);
    }

    public function sendEmailCode($email)
    {
        $validator = new VerifyValidator();

        $validator->checkEmail($email);

        $mailer = new VerifyMailer();

        return $mailer->handle($email);
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
