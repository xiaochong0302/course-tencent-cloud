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
        $service = new StorageService();

        $file = $service->uploadCoverImage();

        if ($file) {
            return $this->jsonSuccess([
                'data' => [
                    'src' => $service->getCiImageUrl($file->path),
                    'title' => $file->name,
                ]
            ]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

    /**
     * @Post("/img/avatar", name="admin.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadAvatarImage();

        if ($file) {
            return $this->jsonSuccess([
                'data' => [
                    'src' => $service->getCiImageUrl($file->path),
                    'title' => $file->name,
                ]
            ]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

    /**
     * @Post("/img/editor", name="admin.upload.editor_img")
     */
    public function uploadEditorImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadEditorImage();

        if ($file) {
            return $this->jsonSuccess([
                'data' => [
                    'src' => $service->getCiImageUrl($file->path),
                    'title' => $file->name,
                ]
            ]);
        } else {
            return $this->jsonError(['msg' => '上传文件失败']);
        }
    }

}
