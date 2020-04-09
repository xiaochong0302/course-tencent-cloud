<?php

namespace App\Services;

use Phalcon\Cache\Backend\Redis as RedisCache;

class Throttle extends Service
{

    public function checkRateLimit()
    {
        $config = $this->getDI()->get('config');

        if ($config->throttle->enabled == false) {
            return true;
        }

        /**
         * @var RedisCache $cache
         */
        $cache = $this->getDI()->get('cache');

        $sign = $this->getRequestSignature();

        $cacheKey = $this->getCacheKey($sign);

        $rateLimit = $cache->get($cacheKey);

        if ($rateLimit) {
            if ($rateLimit >= $config->throttle->rate_limit) {
                return false;
            } else {
                $cache->increment($cacheKey, 1);
            }
        } else {
            $cache->save($cacheKey, 1, $config->throttle->lifetime);
        }

        return true;
    }

    protected function getRequestSignature()
    {
        $authUser = $this->getAuthUser();

        if (!empty($authUser->id)) {
            return md5($authUser->id);
        }

        $httpHost = $this->request->getHttpHost();
        $clientAddress = $this->request->getClientAddress();

        if ($httpHost && $clientAddress) {
            return md5($httpHost . '|' . $clientAddress);
        }

        throw new \RuntimeException('Unable to generate the request signature.');
    }

    protected function getCacheKey($sign)
    {
        return "throttle:{$sign}";
    }

}
