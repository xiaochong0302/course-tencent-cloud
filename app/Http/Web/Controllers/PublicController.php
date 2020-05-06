<?php

namespace App\Http\Web\Controllers;

use App\Models\ContentImage as ContentImageModel;
use App\Services\Storage as StorageService;
use App\Services\Verification as VerifyService;
use App\Traits\Response as ResponseTrait;
use PHPQRCode\QRcode as PHPQRCode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

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
     * @Get("/qrcode/img", name="web.qrcode_img")
     */
    public function qrcodeImageAction()
    {
        $text = $this->request->getQuery('text');
        $level = $this->request->getQuery('level', 'int', 0);
        $size = $this->request->getQuery('size', 'int', 3);

        $url = urldecode($text);

        PHPQRcode::png($url, false, $level, $size);

        $this->response->send();

        exit;
    }

    /**
     * @Post("/sms/code", name="web.sms_code")
     */
    public function smsCodeAction()
    {
        $phone = $this->request->getPost('phone', 'trim');

        $service = new VerifyService();

        $success = $service->sendSmsCode($phone);

        if ($success) {
            return $this->jsonSuccess();
        } else {
            return $this->jsonError([
                'code' => 'verify.send_sms_failed',
            ]);
        }
    }

    /**
     * @Post("/email/code", name="web.email_code")
     */
    public function emailCodeAction()
    {
        $email = $this->request->getPost('email', 'trim');

        $service = new VerifyService();

        $success = $service->sendEmailCode($email);

        if ($success) {
            return $this->jsonSuccess();
        } else {
            return $this->jsonError([
                'code' => 'verify.send_email_failed',
            ]);
        }
    }

}
