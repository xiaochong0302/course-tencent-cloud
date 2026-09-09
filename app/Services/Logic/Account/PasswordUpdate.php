<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Repos\Account as AccountRepo;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;

class PasswordUpdate extends LogicService
{

    public function handle(): AccountModel
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        $accountValidator = new AccountValidator();

        /**
         * 未设置过密码不检查原密码
         */
        if (!empty($account->password)) {
            $accountValidator->checkOriginPassword($account, $post['origin_password']);
        }

        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $accountValidator->checkConfirmPassword($post['new_password'], $post['confirm_password']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;

        $account->update();

        return $account;
    }

}
