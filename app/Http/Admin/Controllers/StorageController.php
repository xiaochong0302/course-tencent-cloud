<?php

namespace App\Http\Admin\Controllers;

use App\Services\Storage as StorageService;

/**
 * @RoutePrefix("/admin/storage")
 */
class StorageController extends Controller
{

    /**
     * @Post("/cover/img/upload", name="admin.storage.cover.img.upload")
     */
    public function uploadCoverImageAction()
    {
        $storageService = new StorageService();

        $key = $storageService->uploadCoverImage();

        $url = $storageService->getCiImageUrl($key);

        if ($url) {
            return $this->ajaxSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->ajaxError(['msg' => '上传文件失败']);
        }
    }

    /**
     * @Post("/content/img/upload", name="admin.content.img.upload")
     */
    public function uploadContentImageAction()
    {
        $storageService = new StorageService();

        $url = $storageService->uploadContentImage();

        if ($url) {
            return $this->ajaxSuccess(['data' => ['src' => $url, 'title' => '']]);
        } else {
            return $this->ajaxError(['msg' => '上传文件失败']);
        }
    }

}
