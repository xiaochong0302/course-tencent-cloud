<?php

namespace App\Http\Admin\Controllers;

use App\Services\MyStorage as StorageService;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/site/logo", name="admin.upload.site_logo")
     */
    public function uploadSiteLogoAction()
    {
        $service = new StorageService();

        $file = $service->uploadSiteLogo();

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
     * @Post("/site/favicon", name="admin.upload.site_favicon")
     */
    public function uploadSiteFaviconAction()
    {
        $service = new StorageService();

        $file = $service->uploadSiteFavicon();

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
     * @Post("/cover/img", name="admin.upload.cover_img")
     */
    public function uploadCoverImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadCoverImage();

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
     * @Post("/avatar/img", name="admin.upload.avatar_img")
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
     * @Post("/content/img", name="admin.upload.content_img")
     */
    public function uploadContentImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadContentImage();

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
     * @Post("/default/img", name="admin.upload.default_img")
     */
    public function uploadDefaultImageAction()
    {
        $service = new StorageService();

        $items = [];

        $items['user_avatar'] = $service->uploadDefaultUserAvatar();
        $items['group_avatar'] = $service->uploadDefaultGroupAvatar();
        $items['course_cover'] = $service->uploadDefaultCourseCover();
        $items['group_cover'] = $service->uploadDefaultPackageCover();
        $items['vip_cover'] = $service->uploadDefaultVipCover();

        foreach ($items as $item) {
            if (!$item) return $this->jsonError(['msg' => '上传文件失败']);
        }

        return $this->jsonSuccess(['msg' => '上传文件成功']);
    }

    /**
     * @Get("/sign", name="admin.upload.sign")
     */
    public function signatureAction()
    {
        $service = new StorageService();

        $token = $service->getFederationToken();

        $data = [
            'credentials' => $token->getCredentials(),
            'expiredTime' => $token->getExpiredTime(),
            'startTime' => time(),
        ];

        return $this->jsonSuccess($data);
    }

}
