<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\WeChatOfficialAccount as WeChatOAService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/wechat/oa")
 */
class WeChatOfficialAccountController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/subscribe/status", name="home.wechat.oa.sub_status")
     */
    public function subscribeStatusAction()
    {
        $service = new WeChatOAService();

        $status = $service->getSubscribeStatus();

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Get("/subscribe/qrcode", name="home.wechat.oa.sub_qrcode")
     */
    public function subscribeQrCodeAction()
    {
        $service = new WeChatOAService();

        $qrcode = $service->createSubscribeQrCode();

        return $this->jsonSuccess(['qrcode' => $qrcode]);
    }

    /**
     * @Get("/notify", name="home.wechat.oa.verify")
     */
    public function verifyAction()
    {
        $service = new WeChatOAService();

        $app = $service->getOfficialAccount();

        $response = $app->server->serve();

        $response->send();

        exit;
    }

    /**
     * @Post("/notify", name="home.wechat.oa.notify")
     */
    public function notifyAction()
    {
        $service = new WeChatOAService();

        $app = $service->getOfficialAccount();

        $app->server->push(function ($message) use ($service) {
            return $service->handleNotify($message);
        });

        $response = $app->server->serve();

        $response->send();

        exit;
    }

}
