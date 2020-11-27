<?php

namespace App\Http\Home\Controllers;

use App\Library\CsrfToken as CsrfTokenService;
use App\Repos\Upload as UploadRepo;
use App\Services\LiveNotify as LiveNotifyService;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Services\Storage as StorageService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use PHPQRCode\QRcode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;

    /**
     * @Get("/download/{md5}", name="home.download")
     */
    public function downloadAction($md5)
    {
        $repo = new UploadRepo();

        $file = $repo->findByMd5($md5);

        if ($file) {

            $service = new StorageService();

            $location = $service->getFileUrl($file->path);

            $this->response->redirect($location, true);

        } else {

            $this->response->setStatusCode(404);

            return $this->response;
        }
    }

    /**
     * @Get("/qrcode", name="home.qrcode")
     */
    public function qrcodeAction()
    {
        $text = $this->request->getQuery('text', 'string');
        $level = $this->request->getQuery('level', 'int', 0);
        $size = $this->request->getQuery('size', 'int', 5);

        $url = urldecode($text);

        QRcode::png($url, false, $level, $size);

        $this->response->send();

        exit;
    }

    /**
     * @Post("/token/refresh", name="home.refresh_token")
     */
    public function refreshTokenAction()
    {
        $this->checkCsrfToken();

        $service = new CsrfTokenService();

        $token = $service->getToken();

        return $this->jsonSuccess(['token' => $token]);
    }

    /**
     * @Post("/alipay/notify", name="home.alipay_notify")
     */
    public function alipayNotifyAction()
    {
        $service = new AlipayService();

        $response = $service->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/wxpay/notify", name="home.wxpay_notify")
     */
    public function wxpayNotifyAction()
    {
        $service = new WxpayService();

        $response = $service->notify();

        if (!$response) exit;

        $response->send();

        exit;
    }

    /**
     * @Post("/live/notify", name="home.live_notify")
     */
    public function liveNotifyAction()
    {
        $service = new LiveNotifyService();

        if ($service->handle()) {
            return $this->jsonSuccess();
        } else {
            $this->response->setStatusCode(403);
        }
    }

}
