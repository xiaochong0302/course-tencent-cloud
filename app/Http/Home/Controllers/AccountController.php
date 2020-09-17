<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Account as AccountService;
use App\Services\Logic\Account\EmailUpdate as EmailUpdateService;
use App\Services\Logic\Account\PasswordReset as PasswordResetService;
use App\Services\Logic\Account\PasswordUpdate as PasswordUpdateService;
use App\Services\Logic\Account\PhoneUpdate as PhoneUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Get("/register", name="home.account.register")
     */
    public function registerAction()
    {
        if ($this->authUser->id > 0) {
            return $this->response->redirect('/');
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $returnUrl = $this->request->getHTTPReferer();

        $this->view->setVar('return_url', $returnUrl);
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/register", name="home.account.do_register")
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
     * @Get("/login", name="home.account.login")
     */
    public function loginAction()
    {
        if ($this->authUser->id > 0) {
            $this->response->redirect('/');
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $returnUrl = $this->request->getHTTPReferer();

        $this->view->setVar('return_url', $returnUrl);
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/login", name="home.account.pwd_login")
     */
    public function loginByPasswordAction()
    {
        $service = new AccountService();

        $service->loginByPassword();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $content = ['location' => $location];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/verify/login", name="home.account.verify_login")
     */
    public function loginByVerifyAction()
    {
        $service = new AccountService();

        $service->loginByVerify();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $content = ['location' => $location];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/logout", name="home.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        $this->response->redirect(['for' => 'home.index']);
    }

    /**
     * @Get("/password/forget", name="home.account.forget_pwd")
     */
    public function forgetPasswordAction()
    {
        if ($this->authUser->id > 0) {
            $this->response->redirect(['for' => 'home.index']);
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $this->view->pick('account/forget_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/password/edit", name="home.account.edit_pwd")
     */
    public function editPasswordAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'home.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_password');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/phone/edit", name="home.account.edit_phone")
     */
    public function editPhoneAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'home.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_phone');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/email/edit", name="home.account.edit_email")
     */
    public function editEmailAction()
    {
        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'home.account.login']);
        }

        $service = new AccountService();

        $captcha = $service->getSettings('captcha');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('account/edit_email');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Post("/password/reset", name="home.account.reset_pwd")
     */
    public function resetPasswordAction()
    {
        $service = new PasswordResetService();

        $service->handle();

        $loginUrl = $this->url->get(['for' => 'home.account.login']);

        $content = [
            'location' => $loginUrl,
            'msg' => '重置密码成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/phone/update", name="home.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new PhoneUpdateService();

        $service->handle();

        $content = ['msg' => '更新手机成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/email/update", name="home.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        $content = ['msg' => '更新邮箱成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/update", name="home.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        $content = ['msg' => '更新密码成功'];

        return $this->jsonSuccess($content);
    }

}
