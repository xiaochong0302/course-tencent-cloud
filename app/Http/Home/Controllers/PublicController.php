<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Common as CommonService;
use App\Library\CsrfToken as CsrfTokenService;
use App\Repos\Upload as UploadRepo;
use App\Services\Logic\Url\ShareUrl as ShareUrlService;
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
     * @Get("/link", name="home.link")
     */
    public function linkAction()
    {
        $commonService = new CommonService();

        $siteInfo = $commonService->getSiteInfo();

        $this->view->setVar('site_info', $siteInfo);
    }

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
        $referer = $this->request->getQuery('referer', 'int');

        $service = new ShareUrlService();

        $location = $service->handle($type, $id, $referer);

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
     * @Get("/alipay/callback", name="home.alipay.callback")
     */
    public function alipayCallbackAction()
    {
        return $this->response->redirect('/h5/#/pages/me/index', true);
    }

    /**
     * @Get("/wxpay/callback", name="home.wxpay.callback")
     */
    public function wxpayCallbackAction()
    {
        return $this->response->redirect('/h5/#/pages/me/index', true);
    }

    /**
     * @Post("/alipay/notify", name="home.alipay.notify")
     */
    public function alipayNotifyAction()
    {
        $service = new AlipayService();

        $response = $service->callback();

        if (!$response) {
            $this->response->setStatusCode(400);
            $this->response->setContent('fail');
        } else {
            $this->response->setStatusCode($response->getStatusCode());
            $this->response->setContent((string)$response->getBody());
        }

        return $this->response;
    }

    /**
     * @Post("/wxpay/notify", name="home.wxpay.notify")
     */
    public function wxpayNotifyAction()
    {
        $service = new WxpayService();

        $response = $service->callback();

        if (!$response) {
            $this->response->setStatusCode(400);
            $this->response->setJsonContent([
                'code' => 'FAIL',
                'message' => '失败',
            ]);
        } else {
            $content = (string)$response->getBody();
            $this->response->setStatusCode($response->getStatusCode());
            $this->response->setJsonContent(json_decode($content, true));
        }

        return $this->response;
    }

    /**
     * @Get("/verify/captcha", name="home.verify.captcha")
     */
    public function verifyCaptchaAction()
    {
        $this->view->pick('public/verify_captcha');
    }

}
