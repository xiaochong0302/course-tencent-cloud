<?php

namespace App\Services\Frontend\My;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\FavoriteList as UserFavoriteListService;

class FavoriteList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserFavoriteListService();

        return $service->handle($user->id);
    }

}
