<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\FriendList as UserFriendListService;

class FriendList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserFriendListService();

        return $service->handle($user->id);
    }

}
