<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
     * @Get("/download/{id}", name="home.download")
     */
    public function downloadAction($id)
    {
        $id = $this->crypt->decryptBase64($id, null, true);

        $repo = new UploadRepo();

        $file = $repo->findById($id);

        if ($file) {

            $service = new StorageService();

            $location = $service->getFileUrl($file->path);

            return $this->response->redirect($location, true);

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
        $id = $this->request->getQuery('id', 'int');
        $type = $this->request->getQuery('type', 'string');

        $service = new ShareUrlService();

        $location = $service->handle($id, $type);

        return $this->response->redirect($location, true);
    }

    /**
     * @Get("/qrcode", name="home.qrcode")
     */
    public function qrcodeAction()
    {
        $text = $this->request->getQuery('text', 'string');
        $size = $this->request->getQuery('size', 'int', 320);
        $margin = $this->request->getQuery('margin', 'int', 10);

        $text = urldecode($text);

        $qrCode = new QrCode($text);

        $qrCode->setSize($size);
        $qrCode->setMargin($margin);

        $this->response->setContentType('image/png');
        $this->response->setContent($qrCode->writeString());

        return $this->response;
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
