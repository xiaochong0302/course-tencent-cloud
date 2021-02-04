<?php

namespace App\Http\Api\Services;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth\Api as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

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

        $token = $this->auth->saveAuthInfo($user);

        $this->fireAfterRegisterEvent($user);

        return $token;
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

        $token = $this->auth->saveAuthInfo($user);

        $this->fireAfterLoginEvent($user);

        return $token;
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

        $token = $this->auth->saveAuthInfo($user);

        $this->fireAfterLoginEvent($user);

        return $token;
    }

    public function logout()
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->fireAfterLogoutEvent($user);
    }

    protected function fireAfterRegisterEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('account:afterRegister', $this, $user);
    }

    protected function fireAfterLoginEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('account:afterLogin', $this, $user);
    }

    protected function fireAfterLogoutEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('account:afterLogout', $this, $user);
    }

}
