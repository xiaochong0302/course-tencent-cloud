<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\WeChatOfficialAccount as HomeWeChatOAService;
use App\Services\Logic\WeChat\OfficialAccount as WeChatOAService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/wechat/oa")
 */
class WeChatOfficialAccountController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/bind", name="home.wechat_oa.bind")
     */
    public function bindAction()
    {
        $this->seo->prependTitle('绑定帐号');

        $this->view->pick('wechat/oa/bind');
    }

    /**
     * @Get("/login/qrcode", name="home.wechat_oa.login_qrcode")
     */
    public function loginQrCodeAction()
    {
        $service = new WeChatOAService();

        $qrcode = $service->createLoginQrCode();

        return $this->jsonSuccess(['qrcode' => $qrcode]);
    }

    /**
     * @Get("/login/status", name="home.wechat_oa.lgoin_status")
     */
    public function loginStatusAction()
    {
        $ticket = $this->request->getQuery('ticket');

        $service = new WeChatOAService();

        $data = $service->getLoginStatus($ticket);

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/subscribe/qrcode", name="home.wechat_oa.subscribe_qrcode")
     */
    public function subscribeQrCodeAction()
    {
        $service = new WeChatOAService();

        $qrcode = $service->createSubscribeQrCode();

        return $this->jsonSuccess(['qrcode' => $qrcode]);
    }

    /**
     * @Get("/subscribe/status", name="home.wechat_oa.subscribe_status")
     */
    public function subscribeStatusAction()
    {
        $service = new WeChatOAService();

        $status = $service->getSubscribeStatus();

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/auth/login", name="home.wechat_oa.auth_login")
     */
    public function authLoginAction()
    {
        $service = new HomeWeChatOAService();

        $service->authLogin();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Post("/bind/login", name="home.wechat_oa.bind_login")
     */
    public function bindLoginAction()
    {
        $service = new HomeWeChatOAService();

        $service->bindLogin();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        return $this->jsonSuccess(['location' => $location]);
    }

    /**
     * @Post("/bind/register", name="home.wechat_oa.bind_register")
     */
    public function bindRegisterAction()
    {
        $service = new HomeWeChatOAService();

        $service->bindRegister();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        return $this->jsonSuccess(['location' => $location]);
    }

}
