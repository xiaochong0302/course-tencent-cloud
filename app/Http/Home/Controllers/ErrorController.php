<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\User as UserModel;
use App\Services\Auth\Home as HomeAuth;
use App\Services\Service as AppService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/error")
 */
class ErrorController extends \Phalcon\Mvc\Controller
{

    /**
     * @var array
     */
    protected $siteInfo;

    /**
     * @var UserModel
     */
    protected $authUser;

    use ResponseTrait;

    public function initialize()
    {
        $this->siteInfo = $this->getSiteInfo();
        $this->authUser = $this->getAuthUser();

        $this->view->setVar('site_info', $this->siteInfo);
        $this->view->setVar('auth_user', $this->authUser);
    }

    /**
     * @Get("/400", name="home.error.400")
     */
    public function show400Action()
    {
        $this->response->setStatusCode(400);

        $messages = $this->flashSession->getMessages('error');

        $message = array_pop($messages);

        $this->view->setVar('message', $message);
    }

    /**
     * @Get("/401", name="home.error.401")
     */
    public function show401Action()
    {
        $this->response->setStatusCode(401);
    }

    /**
     * @Get("/403", name="home.error.403")
     */
    public function show403Action()
    {
        $this->response->setStatusCode(403);
    }

    /**
     * @Get("/404", name="home.error.404")
     */
    public function show404Action()
    {
        $this->response->setStatusCode(404);

        $isAjaxRequest = $this->request->isAjax();
        $isApiRequest = $this->request->isApi();

        if ($isAjaxRequest || $isApiRequest) {
            return $this->jsonError(['code' => 'sys.not_found']);
        }
    }

    /**
     * @Get("/500", name="home.error.500")
     */
    public function show500Action()
    {
        $this->response->setStatusCode(500);
    }

    /**
     * @Get("/503", name="home.error.503")
     */
    public function show503Action()
    {
        $this->response->setStatusCode(503);
    }

    /**
     * @Get("/maintain", name="home.error.maintain")
     */
    public function maintainAction()
    {
        $appService = new AppService();

        $siteInfo = $appService->getSettings('site');

        $this->response->setStatusCode(503);

        $this->view->setVar('message', $siteInfo['closed_tips']);
    }

    protected function getSiteInfo()
    {
        $appService = new AppService();

        return $appService->getSettings('site');
    }

    protected function getAuthUser()
    {
        /**
         * @var HomeAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getCurrentUser();
    }

}
