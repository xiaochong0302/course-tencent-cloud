<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class PasswordReset extends LogicService
{

    use LoginFieldTrait;

    public function handle(): AccountModel
    {
        $post = $this->request->getPost();

        $post = $this->handleLoginFields($post);

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkAccount($post['account']);

        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($post['account'], $post['verify_code']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;

        $account->update();

        return $account;
    }

}
