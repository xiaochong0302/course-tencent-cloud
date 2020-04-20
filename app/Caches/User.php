<?php

namespace App\Caches;

use App\Repos\User as UserRepo;

class User extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "user:{$id}";
    }

    public function getContent($id = null)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        return $user ?: null;
    }

}
