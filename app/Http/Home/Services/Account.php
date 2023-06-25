<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Repos\User as UserRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;
use App\Validators\Captcha as CaptchaValidator;

class Account extends Service
{

    /**
     * @var AuthService
     */
    protected $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function register()
    {
        $service = new RegisterService();

        $account = $service->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterRegister', $this, $user);

        return $user;
    }

    public function loginByPassword()
    {
        $post = $this->request->getPost();

        /**
         * 使用[account|phone|email]做账户名字段兼容
         */
        if (isset($post['phone'])) {
            $post['account'] = $post['phone'];
        } elseif (isset($post['email'])) {
            $post['account'] = $post['email'];
        }

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $validator->checkIfAllowLogin($user);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function loginByVerify()
    {
        $post = $this->request->getPost();

        /**
         * 使用[account|phone|email]做账户名字段兼容
         */
        if (isset($post['phone'])) {
            $post['account'] = $post['phone'];
        } elseif (isset($post['email'])) {
            $post['account'] = $post['email'];
        }

        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($post['account'], $post['verify_code']);

        $validator->checkIfAllowLogin($user);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function logout()
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

}
