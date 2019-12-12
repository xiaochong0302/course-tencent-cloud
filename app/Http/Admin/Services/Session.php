<?php

namespace App\Http\Admin\Services;

use App\Validators\User as UserValidator;

class Session extends Service
{

    /**
     * @var $auth \App\Http\Admin\Services\AuthUser
     */
    protected $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function login()
    {
        $post = $this->request->getPost();

        $validator = new UserValidator();

        $user = $validator->checkLoginAccount($post['account']);

        $validator->checkLoginPassword($user, $post['password']);

        $validator->checkAdminLogin($user);

        $config = new Config();

        $captcha = $config->getSectionConfig('captcha');

        /**
         * 验证码是一次性的，放到最后检查，减少第三方调用
         */
        if ($captcha->enabled) {
            $validator->checkCaptchaCode($post['ticket'], $post['rand']);
        }

        $this->auth->setAuthUser($user);
    }

    public function logout()
    {
        $this->auth->removeAuthUser();
    }

}
