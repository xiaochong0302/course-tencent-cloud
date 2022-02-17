<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Deliver;

use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Services\Logic\Service as LogicService;

class VipDeliver extends LogicService
{

    public function handle(VipModel $vip, UserModel $user)
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();

        $user->vip_expiry_time = strtotime("+{$vip->expiry} months", $baseTime);

        $user->vip = 1;

        $user->update();
    }

}
