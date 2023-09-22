<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Logic\Service as LogicService;

class AccountInfo extends LogicService
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
            'email' => $account->email,
            'phone' => $account->phone,
            'password' => $account->password,
            'create_time' => $account->create_time,
            'update_time' => $account->update_time,
        ];
    }

}
