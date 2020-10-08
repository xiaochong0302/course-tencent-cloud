<?php

namespace App\Http\Admin\Controllers;

use App\Services\Vod as VodService;

/**
 * @RoutePrefix("/admin/vod")
 */
class VodController extends Controller
{

    /**
     * @Get("/upload/sign", name="admin.vod.upload_sign")
     */
    public function uploadSignatureAction()
    {
        $service = new VodService();

        $sign = $service->getUploadSignature();

        return $this->jsonSuccess(['sign' => $sign]);
    }

    /**
     * @Get("/player", name="admin.vod.player")
     */
    public function playerAction()
    {
        $chapterId = $this->request->getQuery('chapter_id', 'int');
        $playUrl = $this->request->getQuery('play_url', 'string');

        $this->view->pick('public/vod_player');
        $this->view->setVar('chapter_id', $chapterId);
        $this->view->setVar('play_url', $playUrl);
    }

}
