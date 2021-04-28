<?php

namespace App\Services\Logic\User\Console;

use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class NotifyStats extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $noticeCount = $this->getNoticeCount($user->id);

        return ['notice_count' => $noticeCount];
    }

    protected function getNoticeCount($userId)
    {
        $userRepo = new UserRepo();

        return $userRepo->countUnreadNotifications($userId);
    }

}
