<?php

namespace App\Http\Web\Controllers;

use App\Models\ContentImage as ContentImageModel;
use App\Services\Storage as StorageService;
use App\Traits\Response as ResponseTrait;
use PHPQRCode\QRcode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Route("/auth", name="web.auth")
     */
    public function authAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '会话已过期，请重新登录']);
        }

        $this->response->redirect(['for' => 'web.login']);
    }

    /**
     * @Route("/robot", name="web.robot")
     */
    public function robotAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '疑似机器人请求']);
        }
    }

    /**
     * @Route("/forbidden", name="web.forbidden")
     */
    public function forbiddenAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '无相关操作权限']);
        }
    }

    /**
     * @Get("/content/img/{id:[0-9]+}", name="web.content.img")
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
     * @Get("/qr/img", name="web.qr.img")
     */
    public function qrImageAction()
    {
        $text = $this->request->getQuery('text');
        $level = $this->request->getQuery('level', 'int', 0);
        $size = $this->request->getQuery('size', 'int', 3);

        $url = urldecode($text);

        QRcode::png($url, false, $level, $size);

        $this->response->send();

        exit;
    }

}
