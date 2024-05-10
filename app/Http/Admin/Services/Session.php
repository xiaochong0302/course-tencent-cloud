<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Services\Auth\Admin as AdminAuth;
use App\Services\Auth\Home as HomeAuth;
use App\Validators\Account as AccountValidator;

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
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkAdminLogin($post['account'], $post['password']);

        $validator->checkIfAllowLogin($user);

        $this->auth->saveAuthInfo($user);

        $this->loginHome($user);

        $this->eventsManager->fire('Account:afterLogin', $this, $user);
    }

    public function logout()
    {
        $user = $this->getLoginUser();

        $this->auth->clearAuthInfo();

        $this->eventsManager->fire('Account:afterLogout', $this, $user);
    }

    protected function loginHome($user)
    {
        $auth = new HomeAuth();

        $auth->saveAuthInfo($user);
    }

}
