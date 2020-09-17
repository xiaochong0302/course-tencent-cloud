<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service;
use App\Services\Logic\User\FavoriteList as UserFavoriteListService;

class FavoriteList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserFavoriteListService();

        return $service->handle($user->id);
    }

}
