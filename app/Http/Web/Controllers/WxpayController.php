<?php

namespace App\Http\Web\Controllers;

use App\Services\Pay\Wxpay as WxpayService;
use App\Traits\Response as ResponseTrait;

class WxpayController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/wxpay/notify", name="web.wxpay.notify")
     */
    public function notifyAction()
    {
        $wxpayService = new WxpayService();

        $response = $wxpayService->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

}
