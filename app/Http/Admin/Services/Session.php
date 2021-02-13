<?php

namespace App\Http\Admin\Services;

use App\Models\User as UserModel;
use App\Services\Auth\Admin as AdminAuth;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNoticeService;
use App\Validators\Account as AccountValidator;
use App\Validators\Captcha as CaptchaValidator;

class Session extends Service
{

    /**
     * @var AdminAuth
     */
    protected $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function login()
    {
        $currentUser = $this->getCurrentUser();

        if ($currentUser->id > 0) {
            $this->response->redirect(['for' => 'home.index']);
        }

        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $user = $accountValidator->checkAdminLogin($post['account'], $post['password']);

        $captchaSettings = $this->getSettings('captcha');

        /**
         * 验证码是一次性的，放到最后检查，减少第三方调用
         */
        if ($captchaSettings['enabled'] == 1) {

            $captchaValidator = new CaptchaValidator();

            $captchaValidator->checkCode($post['ticket'], $post['rand']);
        }

        $this->handleLoginNotice($user);

        $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function logout()
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $service = new AccountLoginNoticeService();

        $service->createTask($user);
    }

}
