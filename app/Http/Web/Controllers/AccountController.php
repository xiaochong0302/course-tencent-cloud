<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Account as AccountService;
use App\Services\Frontend\Account\EmailUpdate as EmailUpdateService;
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
        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $returnUrl = $this->request->getHTTPReferer();

        $this->view->setVar('return_url', $returnUrl);
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/register", name="web.account.do_register")
     */
    public function doRegisterAction()
    {

    }

    /**
     * @Get("/login", name="web.account.login")
     */
    public function loginAction()
    {
        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $returnUrl = $this->request->getHTTPReferer();

        $this->view->setVar('return_url', $returnUrl);
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/login", name="web.account.pwd_login")
     */
    public function loginByPasswordAction()
    {
        $service = new AccountService();

        $service->loginByPassword();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/verify/login", name="web.account.verify_login")
     */
    public function loginByVerifyAction()
    {
        $service = new AccountService();

        $service->loginByVerify();

        return $this->jsonSuccess();
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
     * @Get("/password/forget", name="web.account.forget_pwd")
     */
    public function forgetPasswordAction()
    {
        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/forget_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/reset", name="web.account.reset_pwd")
     */
    public function resetPasswordAction()
    {

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
     * @Post("/password/update", name="web.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '更新密码成功']);
    }

}
