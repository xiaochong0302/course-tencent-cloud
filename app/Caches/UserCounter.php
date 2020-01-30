<?php

namespace App\Caches;

use App\Repos\User as UserRepo;

class UserCounter extends Counter
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "user_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        if (!$user) return [];

        $content = [
            'notice_count' => $user->notice_count,
            'msg_count' => $user->msg_count,
        ];

        return $content;
    }

}
