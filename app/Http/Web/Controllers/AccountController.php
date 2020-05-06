<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Account as AccountService;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Post("/register", name="web.account.register")
     */
    public function registerAction()
    {
        $service = new AccountService();

        $service->signup();

        $location = $this->request->getHTTPReferer();

        $this->response->redirect($location);
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
     * @Post("/phone/update", name="web.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new AccountService();

        $service->updateMobile();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/email/update", name="web.account.update_email")
     */
    public function updateEmailAction()
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

}
