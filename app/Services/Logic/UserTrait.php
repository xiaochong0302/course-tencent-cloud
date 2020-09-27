<?php

namespace App\Services\Logic;

use App\Validators\User as UserValidator;

trait UserTrait
{

    public function checkUser($id)
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    public function checkUserCache($id)
    {
        $validator = new UserValidator();

        return $validator->checkUserCache($id);
    }

}
