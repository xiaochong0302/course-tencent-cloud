<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
