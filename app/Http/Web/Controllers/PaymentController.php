<?php

namespace App\Http\Web\Controllers;

use App\Services\Payment\Alipay as AlipayService;
use App\Services\Payment\Wxpay as WxpayService;
use App\Traits\Response as ResponseTrait;

class PaymentController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/alipay/notify", name="web.alipay.notify")
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
     * @Post("/wxpay/notify", name="web.wxpay.notify")
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
     * @Post("/alipay/status", name="web.alipay.status")
     */
    public function alipayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayService = new AlipayService();

        $status = $alipayService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/wxpay/status", name="web.wxpay.status")
     */
    public function wxpayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $wxpayService = new WxpayService();

        $status = $wxpayService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

}
