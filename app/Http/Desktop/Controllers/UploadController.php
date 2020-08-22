<?php


namespace App\Http\Desktop\Controllers;

use App\Services\MyStorage as StorageService;

/**
 * @RoutePrefix("/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/avatar/img", name="desktop.upload.avatar_img")
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

    /**
     * @Post("/im/img", name="desktop.upload.im_img")
     */
    public function uploadImImageAction()
    {
    }

    /**
     * @Post("/im/file", name="desktop.upload.im_file")
     */
    public function uploadImFileAction()
    {

    }

}