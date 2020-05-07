<?php

namespace App\Http\Web\Services;

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

    public function login()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $user = $accountValidator->checkUserLogin($post['account'], $post['password']);

        $captchaSettings = $this->getSectionSettings('captcha');

        /**
         * 验证码是一次性的，放到最后检查，减少第三方调用
         */
        if ($captchaSettings['enabled'] == 1) {

            $captchaValidator = new CaptchaValidator();

            $captchaValidator->checkCode($post['ticket'], $post['rand']);
        }

        $this->auth->saveAuthInfo($user);
    }

    public function logout()
    {
        $this->auth->clearAuthInfo();
    }

    public function register()
    {
        $service = new RegisterService();

        $user = $service->handle();

        $this->auth->saveAuthInfo($user);
    }

}
