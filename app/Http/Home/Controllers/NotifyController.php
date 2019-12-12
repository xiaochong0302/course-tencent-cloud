<?php

namespace App\Http\Home\Controllers;

use App\Services\Alipay as AlipayService;


class NotifyController extends Controller
{

    /**
     * @Post("/alipay/notify", name="home.alipay.notify")
     */
    public function alipayNotifyAction()
    {
        $alipayService = new AlipayService();

        $response = $alipayService->handleNotify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/wxpay/notify", name="home.wxpay.notify")
     */
    public function wxpayNotifyAction()
    {
        $alipayService = new AlipayService();

        $response = $alipayService->handleNotify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/vod/notify", name="home.vod.notify")
     */
    public function vodNotifyAction()
    {

    }

    /**
     * @Post("/live/notify", name="home.live.notify")
     */
    public function liveNotifyAction()
    {

    }

}
