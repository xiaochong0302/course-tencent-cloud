<?php

namespace App\Http\Home\Services;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;
use App\Validators\Captcha as CaptchaValidator;
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

        $this->auth->saveAuthInfo($user);

        $this->fireAfterRegisterEvent($user);

        return $user;
    }

    public function loginByPassword()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $validator = new CaptchaValidator();

        $validator->checkCode($post['ticket'], $post['rand']);

        $this->auth->saveAuthInfo($user);

        $this->fireAfterLoginEvent($user);
    }

    public function loginByVerify()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($post['account'], $post['verify_code']);

        $this->auth->saveAuthInfo($user);

        $this->fireAfterLoginEvent($user);
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

        $eventsManager->fire('Account:afterRegister', $this, $user);
    }

    protected function fireAfterLoginEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('Account:afterLogin', $this, $user);
    }

    protected function fireAfterLogoutEvent(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->getShared('eventsManager');

        $eventsManager->fire('Account:afterLogout', $this, $user);
    }

}
