<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service;
use App\Validators\Connect as ConnectValidator;

class ConnectDelete extends Service
{

    public function handle($id)
    {
        $user = $this->getLoginUser();

        $validator = new ConnectValidator();

        $connect = $validator->checkConnect($id);

        $validator->checkOwner($user->id, $connect->user_id);

        $connect->deleted = 1;

        $connect->update();
    }

}
