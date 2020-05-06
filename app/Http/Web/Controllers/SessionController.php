<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Account as AccountService;

/**
 * @RoutePrefix("/account")
 */
class SessionController extends Controller
{

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

}
