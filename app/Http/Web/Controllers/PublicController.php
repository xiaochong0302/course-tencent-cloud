<?php

namespace App\Http\Web\Controllers;

use App\Library\CsrfToken as CsrfTokenService;
use App\Models\ContentImage as ContentImageModel;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Services\Storage as StorageService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use PHPQRCode\QRcode as PHPQRCode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;

    /**
     * @Get("/content/img/{id:[0-9]+}", name="web.content_img")
     */
    public function contentImageAction($id)
    {
        $image = ContentImageModel::findFirst($id);

        if (!$image) {

            $this->response->setStatusCode(404);

            return $this->response;
        }

        $storageService = new StorageService();

        $location = $storageService->getCiImageUrl($image->path);

        $this->response->redirect($location);
    }

    /**
     * @Get("/qrcode", name="web.qrcode")
     */
    public function qrcodeAction()
    {
        $text = $this->request->getQuery('text');
        $level = $this->request->getQuery('level', 'int', 0);
        $size = $this->request->getQuery('size', 'int', 5);

        $url = urldecode($text);

        PHPQRcode::png($url, false, $level, $size);

        $this->response->send();

        exit;
    }

    /**
     * @Post("/token/refresh", name="web.refresh_token")
     */
    public function refreshTokenAction()
    {
        $this->checkCsrfToken();

        $service = new CsrfTokenService();

        $token = $service->getToken();

        return $this->jsonSuccess(['token' => $token]);
    }

    /**
     * @Post("/alipay/notify", name="web.alipay_notify")
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
     * @Post("/wxpay/notify", name="web.wxpay_notify")
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
     * @Post("/live/notify", name="web.live_notify")
     */
    public function liveNotifyAction()
    {

    }

}
