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

    }

}
