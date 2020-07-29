<?php

namespace App\Http\Admin\Controllers;

use App\Services\Storage as StorageService;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/img/cover", name="admin.upload.cover_img")
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
     * @Post("/img/avatar", name="admin.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $storageService = new StorageService();

        $key = $storageService->uploadAvatarImage();

        $url = $storageService->getCiImageUrl($key);

        if ($url) {
            return $this->jsonSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

    /**
     * @Post("/img/content", name="admin.upload.content_img")
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
