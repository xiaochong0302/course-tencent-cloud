<?php


namespace App\Http\Api\Controllers;

use App\Services\MyStorage as StorageService;

/**
 * @RoutePrefix("/api/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/avatar/img", name="api.upload.avatar_img")
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
     * @Post("/im/img", name="api.upload.im_img")
     */
    public function uploadImImageAction()
    {

    }

    /**
     * @Post("/im/file", name="api.upload.im_file")
     */
    public function uploadImFileAction()
    {

    }

}