<?php

namespace App\Services\AuthUser;

use App\Caches\AccessToken as AccessTokenCache;
use App\Models\AccessToken as AccessTokenModel;
use App\Models\RefreshToken as RefreshTokenModel;
use App\Models\User as UserModel;
use App\Services\AuthUser;

class Api extends AuthUser
{

    public function saveAuthInfo(UserModel $user)
    {
        $accessToken = new AccessTokenModel();
        $accessToken->user_id = $user->id;
        $accessToken->create();

        $refreshToken = new RefreshTokenModel();
        $refreshToken->user_id = $user->id;
        $refreshToken->create();

        return [
            'access_token' => $accessToken->id,
            'refresh_token' => $refreshToken->id,
            'expiry_time' => $accessToken->expiry_time,
        ];
    }

    public function clearAuthInfo()
    {
        $authToken = $this->getAuthToken();

        $accessTokenCache = new AccessTokenCache();

        /**
         * @var AccessTokenModel $accessToken
         */
        $accessToken = $accessTokenCache->get($authToken);

        if ($accessToken) {

            $accessToken->update(['revoked' => 1]);

            $accessTokenCache->delete($authToken);
        }
    }

    public function getAuthInfo()
    {
        $authToken = $this->getAuthToken();

        $accessTokenCache = new AccessTokenCache();

        return $accessTokenCache->get($authToken);
    }

    public function getAuthToken()
    {
        return $this->request->getHeader('Authorization');
    }

}
