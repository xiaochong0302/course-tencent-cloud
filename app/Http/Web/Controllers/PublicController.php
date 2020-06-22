<?php

namespace App\Http\Web\Controllers;

use App\Library\Security;
use App\Models\ContentImage as ContentImageModel;
use App\Services\Frontend\Chapter\Learning as LearningService;
use App\Services\Storage as StorageService;
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
     * @Post("/token/refresh", name="web.refresh_token")
     */
    public function refreshTokenAction()
    {
        $security = new Security();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="web.learning")
     */
    public function learningAction($id)
    {
        $service = new LearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
