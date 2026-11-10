<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Auth;

use App\Models\KgClient as KgClientModel;
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

        $this->logoutSpecialClients($user->id, KgClientModel::TYPE_PC);

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
        $authInfo = $this->getAuthInfo();

        $userId = $authInfo['id'] ?: null;

        if (!$userId) return;

        $sessionId = $this->session->getId();

        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);

        $this->deleteUserSession($userId, $sessionId);
    }

    public function getAuthInfo(): ?array
    {
        $authKey = $this->getAuthKey();

        $authInfo = $this->session->get($authKey);

        return $authInfo ?: null;
    }

    public function logoutSpecialClients(int $userId, int $clientType): void
    {
        $settings = $this->getSettings('security.access');

        if ($settings['mutex_login'] == 0) return;

        $repo = new UserSessionRepo();

        $records = $repo->findUserActiveSessions($userId, $clientType);

        $activeCount = $records->count();

        $clientLimit = max(intval($settings['mutex_client_limit']), 1);

        if ($activeCount < $clientLimit) return;

        $shouldKickCount = $activeCount - $clientLimit + 1;

        $kickedCount = 0;

        $redis = $this->getRedis();

        foreach ($records as $record) {
            if ($kickedCount < $shouldKickCount) {
                $record->deleted = 1;
                $record->update();
                $key = $this->getSessionCacheKey($record->session_id);
                $redis->del($key);
                $kickedCount++;
            }
        }
    }

    public function logoutAllClients(int $userId): void
    {
        $repo = new UserSessionRepo();

        $records = $repo->findUserActiveSessions($userId);

        if ($records->count() == 0) return;

        $redis = $this->getRedis();

        foreach ($records as $record) {
            $record->deleted = 1;
            $record->update();
            $key = $this->getSessionCacheKey($record->session_id);
            $redis->del($key);
        }
    }

    protected function createUserSession(int $userId, string $sessionId, int $lifetime): void
    {
        $userSession = new UserSessionModel();

        $userSession->user_id = $userId;
        $userSession->session_id = $sessionId;
        $userSession->client_type = KgClientModel::TYPE_PC;
        $userSession->client_ip = $this->getClientIp();
        $userSession->expire_time = time() + $lifetime;

        $userSession->create();
    }

    protected function deleteUserSession(int $userId, string $sessionId): void
    {
        $userSessionRepo = new UserSessionRepo();

        $userSession = $userSessionRepo->findUserSession($userId, $sessionId);

        if ($userSession) {
            $userSession->deleted = 1;
            $userSession->update();
        }
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
