<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        if ($cache->ttl($cacheKey) < 1) {
            $cache->save($cacheKey, 0, $config->path('throttle.lifetime'));
        }

        $rateLimit = $cache->get($cacheKey);

        if ($rateLimit >= $config->path('throttle.rate_limit')) {
            return false;
        }

        $cache->increment($cacheKey, 1);

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

        throw new \RuntimeException('Unable to generate request signature');
    }

    protected function getCacheKey($sign)
    {
        return "throttle:{$sign}";
    }

}
