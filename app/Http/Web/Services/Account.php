<?php

namespace App\Http\Web\Services;

use App\Services\Auth as AuthService;
use App\Services\Frontend\Account\RegisterByEmail as RegisterByEmailService;
use App\Services\Frontend\Account\RegisterByPhone as RegisterByPhoneService;
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

    public function registerByEmail()
    {
        $service = new RegisterByEmailService();

        $user = $service->handle();

        $this->auth->saveAuthInfo($user);
    }

    public function registerByPhone()
    {
        $service = new RegisterByPhoneService();

        $user = $service->handle();

        $this->auth->saveAuthInfo($user);
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
