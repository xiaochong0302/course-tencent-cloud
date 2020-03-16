<?php

namespace App\Services\Frontend;

use App\Validators\User as UserValidator;

trait UserTrait
{

    public function checkUser($id)
    {
        $validator = new UserValidator();

        $user = $validator->checkUser($id);

        return $user;
    }

}
