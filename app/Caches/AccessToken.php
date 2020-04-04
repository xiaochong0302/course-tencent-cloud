<?php

namespace App\Caches;

use App\Repos\AccessToken as AccessTokenRepo;

class AccessToken extends Cache
{

    protected $lifetime = 2 * 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "access_token:{$id}";
    }

    public function getContent($id = null)
    {
        $accessTokenRepo = new AccessTokenRepo();

        $accessToken = $accessTokenRepo->findById($id);

        if (!$accessToken) {
            return new \stdClass();
        }

        return $accessToken;
    }

}
