<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Auth;

use App\Models\User as UserModel;
use App\Models\UserToken as UserTokenModel;
use App\Repos\UserToken as UserTokenRepo;
use App\Services\Auth as AuthService;
use App\Traits\Client as ClientTrait;

class Api extends AuthService
{

    use ClientTrait;

    public function saveAuthInfo(UserModel $user): array
    {
        $token = $this->generateToken($user->id);

        $lifetime = $this->getTokenLifetime();
        $clientType = $this->getClientType();

        $this->logoutSpecialClients($user->id, $clientType);

        $this->createUserToken($user->id, $token, $lifetime);

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $cache->set($key, $authInfo, $lifetime);

        return ['token' => $token];
    }

    public function clearAuthInfo(): void
    {
        $token = $this->request->getHeader('X-Token');

        if (strlen($token) == 0) return;

        $authInfo = $this->getAuthInfo();

        $userId = $authInfo['id'] ?: null;

        if (!$userId) return;

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $cache->delete($key);

        $this->deleteUserToken($userId, $token);
    }

    public function getAuthInfo(): ?array
    {
        $token = $this->request->getHeader('X-Token');

        if (strlen($token) == 0) return null;

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        return $cache->get($key);
    }

    public function logoutSpecialClients(int $userId, int $clientType): void
    {
        $settings = $this->getSettings('security.access');

        if ($settings['mutex_login'] == 0) return;

        $repo = new UserTokenRepo();

        $records = $repo->findUserActiveTokens($userId, $clientType);

        $activeCount = $records->count();

        $clientLimit = max(intval($settings['mutex_client_limit']), 1);

        if ($records->count() < $clientLimit) return;

        $shouldKickCount = $activeCount - $clientLimit + 1;

        $kickedCount = 0;

        $cache = $this->getCache();

        foreach ($records as $record) {
            if ($kickedCount < $shouldKickCount) {
                $record->deleted = 1;
                $record->update();
                $key = $this->getTokenCacheKey($record->token);
                $cache->delete($key);
                $kickedCount++;
            }
        }
    }

    public function logoutAllClients(int $userId): void
    {
        $repo = new UserTokenRepo();

        $records = $repo->findUserActiveTokens($userId);

        if ($records->count() == 0) return;

        $cache = $this->getCache();

        foreach ($records as $record) {
            $record->deleted = 1;
            $record->update();
            $key = $this->getTokenCacheKey($record->token);
            $cache->delete($key);
        }
    }

    protected function createUserToken(int $userId, string $token, int $lifetime): UserTokenModel
    {
        $userToken = new UserTokenModel();

        $userToken->user_id = $userId;
        $userToken->token = $token;
        $userToken->client_type = $this->getClientType();
        $userToken->client_ip = $this->getClientIp();
        $userToken->expire_time = time() + $lifetime;

        $userToken->create();

        return $userToken;
    }

    protected function deleteUserToken(int $userId, string $token): void
    {
        $userTokenRepo = new UserTokenRepo();

        $userToken = $userTokenRepo->findUserToken($userId, $token);

        if ($userToken) {
            $userToken->deleted = 1;
            $userToken->update();
        }
    }

    protected function generateToken(int $userId): string
    {
        return md5(uniqid() . time() . $userId);
    }

    protected function getTokenLifetime(): int
    {
        $config = $this->getConfig();

        return $config->path('api_token.lifetime');
    }

    protected function getTokenCacheKey(string $token): string
    {
        return "api-token-{$token}";
    }

}
