<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Services;

use App\Repos\User as UserRepo;
use App\Services\Auth\Api as AuthService;
use App\Services\Logic\Account\LoginFieldTrait as LoginFieldTrait;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;

class Account extends Service
{

    use LoginFieldTrait;

    /**
     * @var AuthService
     */
    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = $this->getDI()->get('auth');
    }

    public function register(): array
    {
        $service = new RegisterService();

        $account = $service->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $authInfo = $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterRegister', $this, $user);

        return $authInfo;
    }

    public function loginByPassword(): array
    {
        $post = $this->request->getPost();

        $post = $this->handleLoginFields($post);

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $validator->checkIfAllowLogin($user);

        $authInfo = $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);

        return $authInfo;
    }

    public function loginByVerify(): array
    {
        $post = $this->request->getPost();

        $post = $this->handleLoginFields($post);

        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($post['account'], $post['verify_code']);

        $validator->checkIfAllowLogin($user);

        $authInfo = $this->auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);

        return $authInfo;
    }

    public function logout(): void
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

}
