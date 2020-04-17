<?php

namespace App\Http\Web\Controllers;

use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/pay")
 */
class PayController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/alipay/notify", name="web.pay.alipay.notify")
     */
    public function alipayNotifyAction()
    {
        $alipayService = new AlipayService();

        $response = $alipayService->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/wxpay/notify", name="web.pay.wxpay.notify")
     */
    public function wxpayNotifyAction()
    {
        $wxpayService = new WxpayService();

        $response = $wxpayService->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/alipay/status", name="web.pay.alipay.status")
     */
    public function alipayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayService = new AlipayService();

        $status = $alipayService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/wxpay/status", name="web.pay.wxpay.status")
     */
    public function wxpayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $wxpayService = new WxpayService();

        $status = $wxpayService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

}
