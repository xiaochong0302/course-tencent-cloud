<?php

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

        /**
         * demo版本不限制多人登录
         */
        // $this->logoutOtherClients($user->id);

        $this->logoutOtherClients($user->id);

        $this->createUserToken($user->id, $token);

        $cache = $this->getCache();

        $key = $this->getTokenCacheKey($token);

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $config = $this->getConfig();

        $lifetime = $config->path('token.lifetime') ?: 7 * 86400;

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

    protected function createUserToken($userId, $token)
    {
        $userToken = new UserTokenModel();

        $userToken->user_id = $userId;
        $userToken->token = $token;
        $userToken->client_type = $this->getClientType();
        $userToken->client_ip = $this->getClientIp();

        $userToken->create();
    }

    protected function logoutOtherClients($userId)
    {
        $repo = new UserTokenRepo();

        $records = $repo->findByUserId($userId);

        $cache = $this->getCache();

        $clientType = $this->getClientType();

        if ($records->count() == 0) {
            return;
        }

        foreach ($records as $record) {
            if ($record->client_type == $clientType) {
                $record->deleted = 1;
                $record->update();
                $key = $this->getTokenCacheKey($record->token);
                $cache->delete($key);
            }
        }
    }

    protected function generateToken($userId)
    {
        return md5(uniqid() . time() . $userId);
    }

    protected function getTokenCacheKey($token)
    {
        return "_PHCR_TOKEN_:{$token}";
    }

}
