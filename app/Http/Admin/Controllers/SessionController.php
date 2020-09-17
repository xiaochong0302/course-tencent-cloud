<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Session as SessionService;
use App\Http\Admin\Services\Setting as SettingService;
use App\Library\AppInfo as AppInfo;
use App\Traits\Auth as AuthTrait;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;

/**
 * @RoutePrefix("/admin")
 */
class SessionController extends \Phalcon\Mvc\Controller
{

    use AuthTrait;
    use ResponseTrait;
    use SecurityTrait;

    /**
     * @Route("/login", name="admin.login")
     */
    public function loginAction()
    {
        $user = $this->getCurrentUser();

        if ($user->id > 0) {
            $this->response->redirect(['for' => 'admin.index']);
        }

        if ($this->request->isPost()) {

            $this->checkHttpReferer();
            $this->checkCsrfToken();

            $sessionService = new SessionService();

            $sessionService->login();

            $location = $this->url->get(['for' => 'admin.index']);

            return $this->jsonSuccess(['location' => $location]);
        }

        $appInfo = new AppInfo();

        $settingService = new SettingService();

        $captcha = $settingService->getSettings('captcha');

        $this->view->pick('public/login');
        $this->view->setVar('app_info', $appInfo);
        $this->view->setVar('captcha', $captcha);
    }

    /**
     * @Get("/logout", name="admin.logout")
     */
    public function logoutAction()
    {
        $sessionService = new SessionService();

        $sessionService->logout();

        $this->response->redirect(['for' => 'admin.login']);
    }

}
