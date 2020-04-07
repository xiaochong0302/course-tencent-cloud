<?php

namespace App\Http\Admin\Services;

use App\Services\Auth as AuthService;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class Session extends Service
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

        $user = $accountValidator->checkAdminLogin($post['account'], $post['password']);

        $config = new Config();

        $captcha = $config->getSectionConfig('captcha');

        $securityValidator = new SecurityValidator();

        /**
         * 验证码是一次性的，放到最后检查，减少第三方调用
         */
        if ($captcha->enabled) {
            $securityValidator->checkCaptchaCode($post['ticket'], $post['rand']);
        }

        $this->auth->saveAuthInfo($user);
    }

    public function logout()
    {
        $this->auth->clearAuthInfo();
    }

}
