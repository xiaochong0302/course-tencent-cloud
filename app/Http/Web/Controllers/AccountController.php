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
        if ($this->authUser->id > 0) {
            $this->response->redirect('/');
        }

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
        $service = new AccountService();

        $service->register();

        $returnUrl = $this->request->getPost('return_url');

        $content = [
            'location' => $returnUrl ?: '/',
            'msg' => '注册成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/login", name="web.account.login")
     */
    public function loginAction()
    {
        if ($this->authUser->id > 0) {
            $this->response->redirect('/');
        }

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

        $returnUrl = $this->request->getPost('return_url');

        $content = [
            'location' => $returnUrl ?: '/',
            'msg' => '登录成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/verify/login", name="web.account.verify_login")
     */
    public function loginByVerifyAction()
    {
        $service = new AccountService();

        $service->loginByVerify();

        $returnUrl = $this->request->getPost('return_url');

        $content = [
            'location' => $returnUrl ?: '/',
            'msg' => '登录成功',
        ];

        return $this->jsonSuccess($content);
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
        if ($this->authUser->id > 0) {
            $this->response->redirect('/');
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/forget_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/password/edit", name="web.account.edit_pwd")
     */
    public function editPasswordAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'web.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/edit_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/phone/edit", name="web.account.edit_phone")
     */
    public function editPhoneAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'web.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/edit_phone');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/email/edit", name="web.account.edit_email")
     */
    public function editEmailAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'web.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/edit_email');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/reset", name="web.account.reset_pwd")
     */
    public function resetPasswordAction()
    {
        $service = new PasswordResetService();

        $service->handle();

        $loginUrl = $this->url->get(['for' => 'web.account.login']);

        $content = [
            'location' => $loginUrl,
            'msg' => '重置密码成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/phone/update", name="web.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new PhoneUpdateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'web.my.account']),
            'msg' => '更新手机成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/email/update", name="web.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'web.my.account']),
            'msg' => '更新邮箱成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/update", name="web.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        $content = [
            'location' => $this->url->get(['for' => 'web.my.account']),
            'msg' => '更新密码成功',
        ];

        return $this->jsonSuccess($content);
    }

}
