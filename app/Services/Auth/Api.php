<?php

namespace App\Services\Auth;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\AccessToken as AccessTokenModel;
use App\Models\RefreshToken as RefreshTokenModel;
use App\Models\User as UserModel;
use App\Services\Auth as AuthService;
use Yansongda\Supports\Collection;

class Api extends AuthService
{

    public function saveAuthInfo(UserModel $user)
    {
        $config = $this->getDI()->get('config');

        $accessToken = new AccessTokenModel();
        $accessToken->user_id = $user->id;
        $accessToken->expiry_time = time() + $config->access_token->lifetime;
        $accessToken->create();

        $refreshToken = new RefreshTokenModel();
        $refreshToken->user_id = $user->id;
        $refreshToken->expiry_time = time() + $config->refresh_token->lifetime;
        $refreshToken->create();

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $cache = $this->getCache();

        $key = $this->getCacheKey($accessToken->id);

        $cache->save($key, $authInfo, $config->access_token->lifetime);

        return new Collection([
            'access_token' => $accessToken->id,
            'refresh_token' => $refreshToken->id,
            'expiry_time' => $accessToken->expiry_time,
        ]);
    }

    public function clearAuthInfo()
    {
        $authToken = $this->getAuthToken();

        $cache = $this->getCache();

        $key = $this->getCacheKey($authToken);

        $cache->delete($key);
    }

    public function getAuthInfo()
    {
        $authToken = $this->getAuthToken();

        if (!$authToken) return null;

        $cache = $this->getCache();

        $key = $this->getCacheKey($authToken);

        $authInfo = $cache->get($key);

        if (!$authInfo) return null;

        return new Collection($authInfo);
    }

    /**
     * @return RedisCache
     */
    protected function getCache()
    {
        return $this->getDI()->get('cache');
    }

    protected function getAuthToken()
    {
        return $this->request->getHeader('Authorization');
    }

    protected function getCacheKey($token)
    {
        return "access_token:{$token}";
    }

}
