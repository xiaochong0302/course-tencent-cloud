<?php

namespace App\Services\Frontend\My;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\GroupList as UserGroupListService;

class GroupList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserGroupListService();

        return $service->handle($user->id);
    }

}
