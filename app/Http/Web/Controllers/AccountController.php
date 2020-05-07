<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Account as AccountService;
use App\Services\Frontend\Account\EmailUpdate as EmailUpdateService;
use App\Services\Frontend\Account\PasswordReset as PasswordResetService;
use App\Services\Frontend\Account\PasswordUpdate as PasswordUpdateService;
use App\Services\Frontend\Account\PhoneUpdate as PhoneUpdateService;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Route("/login", name="web.account.login")
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->login();

            $location = $this->request->getHTTPReferer();

            return $this->jsonSuccess(['location' => $location]);
        }
    }

    /**
     * @Route("/logout", name="web.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        $this->response->redirect(['for' => 'web.index']);
    }

    /**
     * @Route("/register", name="web.account.register")
     */
    public function registerAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->register();

            $location = $this->request->getHTTPReferer();

            return $this->jsonSuccess([
                'location' => $location,
                'msg' => '注册账户成功',
            ]);
        }
    }

    /**
     * @Route("/password/reset", name="web.account.reset_password")
     */
    public function resetPasswordAction()
    {
        if ($this->request->isPost()) {

            $service = new PasswordResetService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '重置密码成功']);
        }
    }

    /**
     * @Route("/phone/update", name="web.account.update_phone")
     */
    public function updatePhoneAction()
    {
        if ($this->request->isPost()) {

            $service = new PhoneUpdateService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '更新手机成功']);
        }
    }

    /**
     * @Route("/email/update", name="web.account.update_email")
     */
    public function updateEmailAction()
    {
        if ($this->request->isPost()) {

            $service = new EmailUpdateService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '更新邮箱成功']);
        }
    }

    /**
     * @Route("/password/update", name="web.account.update_password")
     */
    public function updatePasswordAction()
    {
        if ($this->request->isPost()) {

            $service = new PasswordUpdateService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '更新密码成功']);
        }
    }

}
