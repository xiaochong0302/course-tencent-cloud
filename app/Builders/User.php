<?php

namespace App\Builders;

use App\Models\User as UserModel;

class User extends Builder
{

    /**
     * @param UserModel $user
     * @return UserModel
     */
    public function handleUser(UserModel $user)
    {
        $user->avatar = kg_ci_img_url($user->avatar);

        return $user;
    }

}
