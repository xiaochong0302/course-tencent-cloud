<?php

namespace App\Http\Admin\Controllers;

use App\Services\Storage as StorageService;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/cover/img", name="admin.upload.cover.img")
     */
    public function uploadCoverImageAction()
    {
        $storageService = new StorageService();

        $key = $storageService->uploadCoverImage();

        $url = $storageService->getCiImageUrl($key);

        if ($url) {
            return $this->jsonSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

    /**
     * @Post("/content/img", name="admin.upload.content.img")
     */
    public function uploadContentImageAction()
    {
        $storageService = new StorageService();

        $url = $storageService->uploadContentImage();

        if ($url) {
            return $this->jsonSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

}
