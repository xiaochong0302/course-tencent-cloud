<?php

namespace App\Services\Logic\User\Console;

use App\Repos\Notification as NotificationRepo;
use App\Services\Logic\Service as LogicService;

class NotificationRead extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $notifyRepo = new NotificationRepo();

        $notifyRepo->markAllAsViewed($user->id);
    }

}
