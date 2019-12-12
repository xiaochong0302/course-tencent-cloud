<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Config as ConfigService;
use App\Http\Admin\Services\Session as SessionService;
use App\Traits\Ajax as AjaxTrait;
use App\Traits\Security as SecurityTrait;

/**
 * @RoutePrefix("/admin")
 */
class SessionController extends \Phalcon\Mvc\Controller
{

    use AjaxTrait;
    use SecurityTrait;

    /**
     * @Route("/login", name="admin.login")
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {

            if (!$this->checkHttpReferer() || !$this->checkCsrfToken()) {
                $this->dispatcher->forward([
                    'controller' => 'public',
                    'action' => 'forbidden',
                ]);
                return false;
            }

            $sessionService = new SessionService();

            $sessionService->login();

            $location = $this->url->get(['for' => 'admin.index']);

            return $this->ajaxSuccess(['location' => $location]);
        }

        $configService = new ConfigService();

        $captcha = $configService->getSectionConfig('captcha');

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
