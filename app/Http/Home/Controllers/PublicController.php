<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ShareUrl as ShareUrlService;
use App\Library\CsrfToken as CsrfTokenService;
use App\Repos\Upload as UploadRepo;
use App\Services\LiveNotify as LiveNotifyService;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Services\Storage as StorageService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Endroid\QrCode\QrCode;

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
     * @Get("/share", name="home.share")
     */
    public function shareAction()
    {
        $id = $this->request->getQuery('id', 'int', 0);
        $type = $this->request->getQuery('type', 'string', 'course');
        $referer = $this->request->getQuery('referer', 'int', 0);

        $service = new ShareUrlService();

        $location = $service->handle($id, $type, $referer);

        return $this->response->redirect($location, true);
    }

    /**
     * @Get("/qrcode", name="home.qrcode")
     */
    public function qrcodeAction()
    {
        $text = $this->request->getQuery('text', 'string');
        $size = $this->request->getQuery('size', 'int', 320);

        $text = urldecode($text);

        $qrCode = new QrCode($text);

        $qrCode->setSize($size);

        $qrCode->getContentType();

        echo $qrCode->writeString();

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
     * @Get("/alipay/callback", name="home.alipay_callback")
     */
    public function alipayCallbackAction()
    {
        return $this->response->redirect('/h5/#/pages/me/index', true);
    }

    /**
     * @Get("/wxpay/callback", name="home.wxpay_callback")
     */
    public function wxpayCallbackAction()
    {
        return $this->response->redirect('/h5/#/pages/me/index', true);
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
