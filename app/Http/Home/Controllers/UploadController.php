<?php


namespace App\Http\Home\Controllers;

use App\Services\MyStorage as StorageService;

/**
 * @RoutePrefix("/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/avatar/img", name="home.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadAvatarImage();

        if (!$file) {
            return $this->jsonError(['msg' => '上传文件失败']);
        }

        $data = [
            'src' => $service->getImageUrl($file->path),
            'title' => $file->name,
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/im/img", name="home.upload.im_img")
     */
    public function uploadImImageAction()
    {
    }

    /**
     * @Post("/im/file", name="home.upload.im_file")
     */
    public function uploadImFileAction()
    {

    }

}