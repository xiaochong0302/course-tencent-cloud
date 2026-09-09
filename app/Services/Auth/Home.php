<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Auth;

use App\Models\User as UserModel;
use App\Models\UserSession as UserSessionModel;
use App\Repos\UserSession as UserSessionRepo;
use App\Services\Auth as AuthService;
use App\Traits\Client as ClientTrait;

class Home extends AuthService
{

    use ClientTrait;

    public function saveAuthInfo(UserModel $user): array
    {
        $sessionId = $this->session->getId();

        $lifetime = $this->getSessionLifetime();

        $this->logoutAllClients($user->id);

        $this->createUserSession($user->id, $sessionId, $lifetime);

        $authKey = $this->getAuthKey();

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $this->session->set($authKey, $authInfo);

        return $authInfo;
    }

    public function clearAuthInfo(): void
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    public function getAuthInfo(): ?array
    {
        $authKey = $this->getAuthKey();

        $authInfo = $this->session->get($authKey);

        return $authInfo ?: null;
    }

    public function logoutAllClients(int $userId): void
    {
        $settings = $this->getSettings('security.access');

        $clientLimit = max($settings['mutex_client_limit'], 1);

        if ($settings['mutex_login'] == 0) return;

        $repo = new UserSessionRepo();

        $records = $repo->findUserActiveSessions($userId);

        if ($records->count() < $clientLimit) return;

        $shouldKickCount = $records->count() - $clientLimit;

        $kickedCount = 0;

        $cache = $this->getCache();

        foreach ($records as $record) {
            if ($kickedCount < $shouldKickCount) {
                $key = $this->getSessionCacheKey($record->session_id);
                $cache->delete($key);
                $kickedCount++;
            }
        }
    }

    protected function createUserSession(int $userId, string $sessionId, int $lifetime): void
    {
        $userSession = new UserSessionModel();

        $userSession->user_id = $userId;
        $userSession->session_id = $sessionId;
        $userSession->client_type = $this->getClientType();
        $userSession->client_ip = $this->getClientIp();
        $userSession->expire_time = time() + $lifetime;

        $userSession->create();
    }

    protected function getSessionLifetime(): int
    {
        $config = $this->getConfig();

        return $config->path('session.lifetime');
    }

    protected function getSessionCacheKey(string $sessionId): string
    {
        $config = $this->getConfig();

        $prefix = $config->path('session.prefix');

        return $prefix . $sessionId;
    }

    protected function getAuthKey(): string
    {
        return 'home-auth-info';
    }

}
