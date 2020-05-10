<?php

namespace App\Http\Web\Controllers;

use App\Services\Pay\Alipay as AlipayService;
use App\Traits\Response as ResponseTrait;

class AlipayController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/alipay/notify", name="web.alipay.notify")
     */
    public function notifyAction()
    {
        $alipayService = new AlipayService();

        $response = $alipayService->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

}
