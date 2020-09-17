<?php

namespace App\Services;

class Throttle extends Service
{

    public function checkRateLimit()
    {
        $config = $this->getConfig();

        if ($config->path('throttle.enabled') == false) {
            return true;
        }

        $cache = $this->getCache();

        $sign = $this->getRequestSignature();

        $cacheKey = $this->getCacheKey($sign);

        $rateLimit = $cache->get($cacheKey);

        if ($rateLimit) {
            if ($rateLimit >= $config->path('throttle.rate_limit')) {
                return false;
            } else {
                $cache->increment($cacheKey, 1);
            }
        } else {
            $cache->save($cacheKey, 1, $config->path('throttle.lifetime'));
        }

        return true;
    }

    protected function getRequestSignature()
    {
        $authUser = $this->getAuthUser();

        if (!empty($authUser['id'])) {
            return md5($authUser['id']);
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
