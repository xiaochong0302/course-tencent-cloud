<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service;
use App\Services\Logic\User\FriendList as UserFriendListService;

class FriendList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserFriendListService();

        return $service->handle($user->id);
    }

}
