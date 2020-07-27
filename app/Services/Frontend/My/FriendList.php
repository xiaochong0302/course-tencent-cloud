<?php

namespace App\Services\Frontend\My;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\FriendList as UserFriendListService;

class FriendList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserFriendListService();

        return $service->handle($user->id);
    }

}
