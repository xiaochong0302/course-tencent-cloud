<?php

namespace App\Http\Home\Services;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNoticeService;
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

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $validator = new CaptchaValidator();

        $validator->checkCode($post['ticket'], $post['rand']);

        $this->handleLoginNotice($user);

        $this->auth->saveAuthInfo($user);
    }

    public function loginByVerify()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($post['account'], $post['verify_code']);

        $this->handleLoginNotice($user);

        $this->auth->saveAuthInfo($user);
    }

    public function logout()
    {
        $this->auth->clearAuthInfo();
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $service = new AccountLoginNoticeService();

        $service->createTask($user);
    }

}
