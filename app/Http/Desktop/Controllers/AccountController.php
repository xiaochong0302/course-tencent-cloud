<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\Account as AccountService;
use App\Services\Frontend\Account\EmailUpdate as EmailUpdateService;
use App\Services\Frontend\Account\PasswordReset as PasswordResetService;
use App\Services\Frontend\Account\PasswordUpdate as PasswordUpdateService;
use App\Services\Frontend\Account\PhoneUpdate as PhoneUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Get("/register", name="desktop.account.register")
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
     * @Post("/register", name="desktop.account.do_register")
     */
    public function doRegisterAction()
    {
        $service = new AccountService();

        $service->register();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $content = [
            'location' => $returnUrl ?: '/',
            'msg' => '注册成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/login", name="desktop.account.login")
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
     * @Post("/password/login", name="desktop.account.pwd_login")
     */
    public function loginByPasswordAction()
    {
        $service = new AccountService();

        $service->loginByPassword();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'desktop.index']);

        $content = ['location' => $location];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/verify/login", name="desktop.account.verify_login")
     */
    public function loginByVerifyAction()
    {
        $service = new AccountService();

        $service->loginByVerify();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'desktop.index']);

        $content = ['location' => $location];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/logout", name="desktop.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        $this->response->redirect(['for' => 'desktop.index']);
    }

    /**
     * @Get("/password/forget", name="desktop.account.forget_pwd")
     */
    public function forgetPasswordAction()
    {
        if ($this->authUser->id > 0) {
            $this->response->redirect(['for' => 'desktop.index']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->pick('account/forget_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/password/edit", name="desktop.account.edit_pwd")
     */
    public function editPasswordAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'desktop.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/phone/edit", name="desktop.account.edit_phone")
     */
    public function editPhoneAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'desktop.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_phone');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/email/edit", name="desktop.account.edit_email")
     */
    public function editEmailAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'desktop.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSectionSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_email');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/reset", name="desktop.account.reset_pwd")
     */
    public function resetPasswordAction()
    {
        $service = new PasswordResetService();

        $service->handle();

        $loginUrl = $this->url->get(['for' => 'desktop.account.login']);

        $content = [
            'location' => $loginUrl,
            'msg' => '重置密码成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/phone/update", name="desktop.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new PhoneUpdateService();

        $service->handle();

        $content = ['msg' => '更新手机成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/email/update", name="desktop.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        $content = ['msg' => '更新邮箱成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/update", name="desktop.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        $content = ['msg' => '更新密码成功'];

        return $this->jsonSuccess($content);
    }

}
