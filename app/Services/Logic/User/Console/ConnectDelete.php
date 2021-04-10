<?php

namespace App\Services\Logic\User\Console;

use App\Validators\Connect as ConnectValidator;

class ConnectDelete extends LogicService
{

    public function handle($id)
    {
        $user = $this->getLoginUser();

        $validator = new ConnectValidator();

        $connect = $validator->checkConnect($id);

        $validator->checkOwner($user->id, $connect->user_id);

        $connect->delete();
    }

}
