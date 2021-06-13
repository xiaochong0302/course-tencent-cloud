<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class BalanceInfo extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $userRepo = new UserRepo();

        $balance = $userRepo->findUserBalance($user->id);

        if (!$balance) {
            return [
                'cash' => 0.00,
                'point' => 0,
            ];
        }

        return [
            'cash' => $balance->cash,
            'point' => $balance->point,
        ];
    }

}
