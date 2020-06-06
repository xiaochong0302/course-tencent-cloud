<?php

namespace App\Services\Frontend\My;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Frontend\Service as FrontendService;

class AccountInfo extends FrontendService
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
