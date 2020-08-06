<?php

namespace App\Http\Web\Controllers;

use App\Library\CsrfToken as CsrfTokenService;
use App\Repos\UploadFile as UploadFileRepo;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Services\Storage as StorageService;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Text;
use PHPQRCode\QRcode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use SecurityTrait;

    /**
     * @Get("/img/{id:[0-9]+}", name="web.img")
     */
    public function imageAction($id)
    {
        $repo = new UploadFileRepo();

        $file = $repo->findById($id);

        if ($file && Text::startsWith($file->mime, 'image')) {

            $service = new StorageService();

            $location = $service->getCiImageUrl($file->path);

            $this->response->redirect($location);

        } else {

            $this->response->setStatusCode(404);

            return $this->response;
        }
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

        QRcode::png($url, false, $level, $size);

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
