<?php

namespace App\Http\Home\Controllers;

use App\Models\ContentImage as ContentImageModel;
use App\Services\Storage as StorageService;
use App\Traits\Ajax as AjaxTrait;
use PHPQRCode\QRcode;

class PublicController extends \Phalcon\Mvc\Controller
{

    use AjaxTrait;

    /**
     * @Route("/auth", name="home.auth")
     */
    public function authAction()
    {
        if ($this->request->isAjax()) {
            return $this->ajaxError(['msg' => '会话已过期，请重新登录']);
        }

        $this->response->redirect(['for' => 'home.login']);
    }

    /**
     * @Route("/robot", name="home.robot")
     */
    public function robotAction()
    {
        if ($this->request->isAjax()) {
            return $this->ajaxError(['msg' => '疑似机器人请求']);
        }
    }

    /**
     * @Route("/forbidden", name="home.forbidden")
     */
    public function forbiddenAction()
    {
        if ($this->request->isAjax()) {
            return $this->ajaxError(['msg' => '无相关操作权限']);
        }
    }

    /**
     * @Get("/content/img/{id:[0-9]+}", name="home.content.img")
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
     * @Get("/qrcode/img", name="home.qrcode.img")
     */
    public function qrcodeImageAction()
    {
        $text = $this->request->getQuery('text');
        $level = $this->request->getQuery('level', 'int', 0);
        $size = $this->request->getQuery('size', 'int', 3);

        $url = urldecode($text);

        echo QRcode::png($url, false, $level, $size);

        exit;
    }

}
