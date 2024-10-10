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

    public function saveAuthInfo(UserModel $user)
    {
        $token = $this->generateToken($user->id);

        $lifetime = $this->getTokenLifetime();

        $clientType = $this->getClientType();

        $this->logoutClients($user->id, $clientType);

        $this->createUserToken($user->id, $token, $lifetime);

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $cache->save($key, $authInfo, $lifetime);

        return $token;
    }

    public function clearAuthInfo()
    {
        $token = $this->request->getHeader('X-Token');

        if (empty($token)) return null;

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $cache->delete($key);
    }

    public function getAuthInfo()
    {
        $token = $this->request->getHeader('X-Token');

        if (empty($token)) return null;

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $authInfo = $cache->get($key);

        return $authInfo ?: null;
    }

    public function logoutClients($userId, $clientType = null)
    {
        $repo = new UserTokenRepo();

        $records = $repo->findUserActiveTokens($userId);

        if ($records->count() == 0) return;

        if ($clientType) {
            $this->logoutSpecialClients($records, $clientType);
        } else {
            $this->logoutAllClients($records);
        }
    }

    /**
     * @param $userTokens UserTokenModel[]
     */
    protected function logoutAllClients($userTokens)
    {
        $cache = $this->getCache();

        foreach ($userTokens as $record) {
            $key = $this->getTokenCacheKey($record->token);
            $cache->delete($key);
        }
    }

    /**
     * @param $userTokens UserTokenModel[]
     * @param int $clientType
     */
    protected function logoutSpecialClients($userTokens, $clientType)
    {
        $cache = $this->getCache();

        foreach ($userTokens as $record) {
            if ($record->client_type == $clientType) {
                $key = $this->getTokenCacheKey($record->token);
                $cache->delete($key);
            }
        }
    }

    protected function createUserToken($userId, $token, $lifetime)
    {
        $userToken = new UserTokenModel();

        $userToken->user_id = $userId;
        $userToken->token = $token;
        $userToken->client_type = $this->getClientType();
        $userToken->client_ip = $this->getClientIp();
        $userToken->expire_time = time() + $lifetime;

        $userToken->create();
    }

    protected function generateToken($userId)
    {
        return md5(uniqid() . time() . $userId);
    }

    protected function getTokenLifetime()
    {
        $config = $this->getConfig();

        return $config->path('token.lifetime') ?: 7 * 86400;
    }

    protected function getTokenCacheKey($token)
    {
        return "_PHCR_TOKEN_:{$token}";
    }

}
