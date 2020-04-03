<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Account as AccountService;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Post("/signup", name="web.account.signup")
     */
    public function signupAction()
    {
        $service = new AccountService();

        $service->signup();

        $location = $this->request->getHTTPReferer();

        $this->response->redirect($location);
    }

    /**
     * @Route("/login", name="web.account.login")
     */
    public function loginAction()
    {
        $service = new AccountService();

        $service->login();

        $location = $this->request->getHTTPReferer();

        $this->response->redirect($location);
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
     * @Route("/password/reset", name="web.account.reset_password")
     */
    public function resetPasswordAction()
    {
        $service = new AccountService();

        $service->resetPassword();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/mobile/update", name="web.account.update_mobile")
     */
    public function updateMobileAction()
    {
        $service = new AccountService();

        $service->updateMobile();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/password/update", name="web.account.update_password")
     */
    public function updatePasswordAction()
    {
        $service = new AccountService();

        $service->updatePassword();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/captcha/send", name="web.account.send_captcha")
     */
    public function sendCaptchaAction()
    {
        $service = new AccountService();

        $service->sendCaptcha();

        return $this->jsonSuccess();
    }

}
