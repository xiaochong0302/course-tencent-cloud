<?php

namespace App\Services\Logic\User\Console;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Logic\Service;

class AccountInfo extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        return $this->handleAccount($user);
    }

    protected function handleAccount(UserModel $user)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        return [
            'id' => $account->id,
            'phone' => $account->phone,
            'email' => $account->email,
        ];
    }

}
