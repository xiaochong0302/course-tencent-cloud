<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Session as SessionService;
use App\Http\Admin\Services\Setting as SettingService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;

/**
 * @RoutePrefix("/admin")
 */
class SessionController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait, SecurityTrait;

    /**
     * @Route("/login", name="admin.login")
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {

            $this->checkHttpReferer();
            $this->checkCsrfToken();

            $sessionService = new SessionService();

            $sessionService->login();

            $location = $this->url->get(['for' => 'admin.index']);

            return $this->jsonSuccess(['location' => $location]);
        }

        $settingService = new SettingService();

        $captcha = $settingService->getSectionSettings('captcha');

        $this->view->pick('public/login');
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/logout", name="admin.logout")
     */
    public function logoutAction()
    {
        $service = new SessionService();

        $service->logout();

        $this->response->redirect(['for' => 'admin.login']);
    }

}
