<?php

namespace App\Services\AuthUser;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\User as UserModel;
use App\Services\AuthUser;

class Api extends AuthUser
{

    public function saveAuthInfo(UserModel $user)
    {
        $authUser = new \stdClass();

        $authUser->id = $user->id;
        $authUser->name = $user->name;
        $authUser->avatar = $user->avatar;
        $authUser->admin_role = $user->admin_role;
        $authUser->edu_role = $user->edu_role;

        $authToken = $this->getRandToken($user->id);

        $cacheKey = $this->getCacheKey($authToken);

        $cache = $this->getCache();

        $cache->save($cacheKey, $authUser);
    }

    public function clearAuthInfo()
    {
        $authToken = $this->getAuthToken();

        $cacheKey = $this->getCacheKey($authToken);

        $cache = $this->getCache();

        $cache->delete($cacheKey);
    }

    public function getAuthInfo()
    {
        $authToken = $this->getAuthToken();

        $cacheKey = $this->getCacheKey($authToken);

        $cache = $this->getCache();

        return $cache->get($cacheKey);
    }

    public function getAuthToken()
    {
        return $this->request->getHeader('Authorization');
    }

    public function getCacheKey($token)
    {
        return "token:{$token}";
    }

    public function getRandToken($userId)
    {
        return md5($userId . time() . rand(1000, 9999));
    }

    /**
     * @return RedisCache
     */
    public function getCache()
    {
        return $this->getDI()->get('cache');
    }

}
