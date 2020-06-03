<?php

namespace App\Http\Web\Services;

use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Services\Frontend\Account\Register as RegisterService;
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

        return $user;
    }

    public function loginByPassword()
    {
        $post = $this->request->getPost();

        $captchaValidator = new CaptchaValidator();

        $captchaValidator->checkCode($post['ticket'], $post['rand']);

        $accountValidator = new AccountValidator();

        $user = $accountValidator->checkUserLogin($post['account'], $post['password']);

        $this->auth->saveAuthInfo($user);
    }

    public function loginByVerify()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $user = $accountValidator->checkVerifyLogin($post['account'], $post['verify_code']);

        $this->auth->saveAuthInfo($user);
    }

    public function logout()
    {
        $this->auth->clearAuthInfo();
    }

}
