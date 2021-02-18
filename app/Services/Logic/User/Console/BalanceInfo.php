<?php

namespace App\Services\Logic\User\Console;

use App\Repos\User as UserRepo;
use App\Services\Logic\Service;

class BalanceInfo extends Service
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
