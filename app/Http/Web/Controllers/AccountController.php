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
     * @Get("/register", name="web.account.register")
     */
    public function registerAction()
    {

    }

    /**
     * @Get("/login", name="web.account.login")
     */
    public function loginAction()
    {

    }

    /**
     * @Get("/logout", name="web.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        $this->response->redirect(['for' => 'web.index']);
    }

    /**
     * @Get("/password/reset", name="web.account.reset_password")
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
     * @Post("/register_by_phone", name="web.account.register_by_phone")
     */
    public function registerByPhoneAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->registerByPhone();

            $content = [
                'location' => $this->request->getHTTPReferer(),
                'msg' => '注册账户成功',
            ];

            return $this->jsonSuccess($content);
        }
    }

    /**
     * @Post("/register_by_email", name="web.account.register_by_email")
     */
    public function registerByEmailAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->registerByPhone();

            $content = [
                'msg' => '注册账户成功',
            ];

            return $this->jsonSuccess($content);
        }
    }

    /**
     * @Post("/login_by_pwd", name="web.account.login_by_pwd")
     */
    public function loginByPasswordAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->loginByPassword();

            return $this->jsonSuccess();
        }
    }

    /**
     * @Post("/login_by_verify", name="web.account.login_by_verify")
     */
    public function loginByVerifyAction()
    {
        if ($this->request->isPost()) {

            $service = new AccountService();

            $service->loginByVerify();

            return $this->jsonSuccess();
        }
    }

    /**
     * @Post("/password/reset_by_email", name="web.account.reset_pwd_by_email")
     */
    public function resetPasswordByEmailAction()
    {
        if ($this->request->isPost()) {

            $service = new PasswordResetService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '重置密码成功']);
        }
    }

    /**
     * @Post("/password/reset_by_phone", name="web.account.reset_pwd_by_phone")
     */
    public function resetPasswordByPhoneAction()
    {
        if ($this->request->isPost()) {

            $service = new PasswordResetService();

            $service->handle();

            return $this->jsonSuccess(['msg' => '重置密码成功']);
        }
    }

    /**
     * @Post("/phone/update", name="web.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new PhoneUpdateService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '更新手机成功']);
    }

    /**
     * @Post("/email/update", name="web.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '更新邮箱成功']);
    }

    /**
     * @Post("/password/update", name="web.account.update_password")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '更新密码成功']);
    }

}
