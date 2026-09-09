<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

class ThrottleLimit extends Service
{

    public function checkRateLimit(): bool
    {
        $settings = $this->getSettings('security.throttle');

        $settings['interval'] = max($settings['interval'], 60);
        $settings['rate_limit'] = max($settings['rate_limit'], 60);

        if ($settings['enabled'] == 0) return true;

        $redis = $this->getRedis();

        $sign = $this->getRequestSignature();

        $cacheKey = $this->getCacheKey($sign);

        $requestCount = $redis->incr($cacheKey);

        if ($requestCount === 1) {
            $redis->expire($cacheKey, $settings['interval']);
        }

        if ($requestCount > $settings['rate_limit']) {
            return false;
        }

        return true;
    }

    protected function getRequestSignature(): string
    {
        $authUser = $this->getCurrentUser(true);

        if ($authUser->id > 0) {
            return crc32($authUser->id);
        }

        $httpHost = $this->request->getHttpHost();
        $clientAddress = $this->request->getClientAddress();

        if ($httpHost && $clientAddress) {
            return crc32($httpHost . '|' . $clientAddress);
        }

        throw new \RuntimeException('Unable to generate request signature');
    }

    protected function getCacheKey(string $sign): string
    {
        return "throttle-limit-{$sign}";
    }

}
