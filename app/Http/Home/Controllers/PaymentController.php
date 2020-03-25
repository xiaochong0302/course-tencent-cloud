<?php

namespace App\Http\Home\Controllers;

use App\Services\Payment\Alipay as AlipayService;
use App\Services\Payment\Wxpay as WxpayService;
use App\Traits\Ajax as AjaxTrait;

class PaymentController extends \Phalcon\Mvc\Controller
{

    use AjaxTrait;

    /**
     * @Post("/alipay/notify", name="home.alipay.notify")
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
     * @Post("/wxpay/notify", name="home.wxpay.notify")
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
     * @Post("/alipay/status", name="home.alipay.status")
     */
    public function alipayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayService = new AlipayService();

        $status = $alipayService->status($sn);

        return $this->ajaxSuccess(['status' => $status]);
    }

    /**
     * @Post("/wxpay/status", name="home.wxpay.status")
     */
    public function wxpayStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $wxpayService = new WxpayService();

        $status = $wxpayService->status($sn);

        return $this->ajaxSuccess(['status' => $status]);
    }

}
