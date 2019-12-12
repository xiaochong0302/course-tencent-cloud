<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Account as AccountService;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Post("/signup", name="home.account.signup")
     */
    public function signupAction()
    {
        $service = new AccountService();

        $service->signup();

        $location = $this->request->getHTTPReferer();

        $this->response->redirect($location);
    }

    /**
     * @Route("/login", name="home.account.login")
     */
    public function loginAction()
    {
        $service = new AccountService();

        $service->login();

        $location = $this->request->getHTTPReferer();

        $this->response->redirect($location);
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
     * @Route("/password/reset", name="home.account.reset_password")
     */
    public function resetPasswordAction()
    {
        $service = new AccountService();

        $service->resetPassword();

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/mobile/update", name="home.account.update_mobile")
     */
    public function updateMobileAction()
    {
        $service = new AccountService();

        $service->updateMobile();

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/password/update", name="home.account.update_password")
     */
    public function updatePasswordAction()
    {
        $service = new AccountService();

        $service->updatePassword();

        return $this->ajaxSuccess();
    }

    /**
     * @Post("/captcha/send", name="home.account.send_captcha")
     */
    public function sendCaptchaAction()
    {
        $service = new AccountService();

        $service->sendCaptcha();

        return $this->ajaxSuccess();
    }

}
