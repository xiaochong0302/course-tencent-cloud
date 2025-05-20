<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Connect as ConnectService;
use App\Models\Connect as ConnectModel;

/**
 * @RoutePrefix("/oauth")
 */
class ConnectController extends Controller
{

    /**
     * @Get("/qq", name="home.oauth.qq")
     */
    public function qqAction()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectModel::PROVIDER_QQ);

        return $this->response->redirect($url, true);
    }

    /**
     * @Get("/weixin", name="home.oauth.weixin")
     */
    public function weixinAction()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectModel::PROVIDER_WEIXIN);

        return $this->response->redirect($url, true);
    }

    /**
     * @Get("/weibo", name="home.oauth.weibo")
     */
    public function weiboAction()
    {
        $service = new ConnectService();

        $url = $service->getAuthorizeUrl(ConnectModel::PROVIDER_WEIBO);

        return $this->response->redirect($url, true);
    }

    /**
     * @Get("/qq/callback", name="home.oauth.qq_callback")
     */
    public function qqCallbackAction()
    {
        $this->handleCallback(ConnectModel::PROVIDER_QQ);
    }

    /**
     * @Get("/weixin/callback", name="home.oauth.weixin_callback")
     */
    public function weixinCallbackAction()
    {
        $this->handleCallback(ConnectModel::PROVIDER_WEIXIN);
    }

    /**
     * @Get("/weibo/callback", name="home.oauth.weibo_callback")
     */
    public function weiboCallbackAction()
    {
        $this->handleCallback(ConnectModel::PROVIDER_WEIBO);
    }

    /**
     * @Get("/weibo/refuse", name="home.oauth.weibo_refuse")
     */
    public function weiboRefuseAction()
    {
        return $this->response->redirect(['for' => 'home.account.login']);
    }

    /**
     * @Post("/bind/login", name="home.oauth.bind_login")
     */
    public function bindLoginAction()
    {
        $service = new ConnectService();

        $service->bindLogin();

        $location = $this->url->get(['for' => 'home.uc.account']);

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Post("/bind/register", name="home.oauth.bind_register")
     */
    public function bindRegisterAction()
    {
        $service = new ConnectService();

        $service->bindRegister();

        $location = $this->url->get(['for' => 'home.uc.account']);

        return $this->jsonSuccess(['location' => $location]);
    }

    protected function handleCallback($provider)
    {
        $code = $this->request->getQuery('code');
        $state = $this->request->getQuery('state');

        $service = new ConnectService();

        $openUser = $service->getOpenUserInfo($code, $state, $provider);
        $connect = $service->getConnectRelation($openUser['id'], $openUser['provider']);

        if ($this->authUser->id > 0 && $openUser) {
            $service->bindUser($openUser);
            return $this->response->redirect(['for' => 'home.uc.account']);
        }

        if ($this->authUser->id == 0 && $connect) {
            $service->authConnectLogin($connect);
            return $this->response->redirect(['for' => 'home.index']);
        }

        $this->seo->prependTitle('绑定帐号');

        $this->view->pick('connect/bind');
        $this->view->setVar('provider', $provider);
        $this->view->setVar('open_user', $openUser);
    }

}
