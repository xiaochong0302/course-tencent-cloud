<?php


namespace App\Http\Web\Controllers;

use App\Services\Storage as StorageService;

/**
 * @RoutePrefix("/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/img/avatar", name="web.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new StorageService();

        $key = $service->uploadAvatarImage();

        $url = $service->getCiImageUrl($key);

        if ($url) {
            return $this->jsonSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

}