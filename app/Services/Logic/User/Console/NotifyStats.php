<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
