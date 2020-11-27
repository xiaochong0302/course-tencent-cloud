<?php

namespace App\Http\Api\Services;

use App\Repos\User as UserRepo;
use App\Services\Auth\Api as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;

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

        return $this->auth->saveAuthInfo($user);
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

        return $this->auth->saveAuthInfo($user);
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

        return $this->auth->saveAuthInfo($user);
    }

    public function logout()
    {
        $this->auth->clearAuthInfo();
    }

}
