<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

class LoginLimit extends Service
{

    public function checkFailedLogin(int $userId): bool
    {
        $settings = $this->getSettings('security.access');

        $redis = $this->getRedis();

        $key = $this->getCacheKey($userId);

        $failedCount = $redis->get($key);

        if (!$failedCount) return true;

        $maxAttempts = max($settings['failed_login_limit'], 3);

        return $maxAttempts > $failedCount;
    }

    public function incrFailedLogin(int $userId): void
    {
        $settings = $this->getSettings('security.access');

        $lifetime = max($settings['failed_login_lock'], 60);

        $redis = $this->getRedis();

        $key = $this->getCacheKey($userId);

        $failedCount = $redis->get($key);

        if ($failedCount) {
            $redis->incr($key, 1);
        } else {
            $redis->set($key, 1, ['ex' => $lifetime]);
        }
    }

    protected function getCacheKey(int $userId): string
    {
        return "login-limit-{$userId}";
    }

}
