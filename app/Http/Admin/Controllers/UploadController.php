<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Upload as UploadService;
use App\Services\MyStorage as StorageService;
use App\Services\Vod as VodService;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    /**
     * @Post("/icon/img", name="admin.upload.icon_img")
     */
    public function uploadIconImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadIconImage();

        if (!$file) {
            return $this->jsonError(['msg' => '上传文件失败']);
        }

        $data = [
            'id' => $file->id,
            'name' => $file->name,
            'url' => $service->getImageUrl($file->path),
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
            'id' => $file->id,
            'name' => $file->name,
            'url' => $service->getImageUrl($file->path),
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
            'id' => $file->id,
            'name' => $file->name,
            'url' => $service->getImageUrl($file->path),
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
            'id' => $file->id,
            'name' => $file->name,
            'url' => $service->getImageUrl($file->path),
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/content/img/remote", name="admin.upload.remote_content_img")
     */
    public function uploadRemoteContentImageAction()
    {
        $originalUrl = $this->request->getPost('url', ['trim', 'string']);

        $service = new StorageService();

        $file = $service->uploadRemoteContentImage($originalUrl);

        $newUrl = $originalUrl;

        if ($file) {
            $newUrl = $service->getImageUrl($file->path);
        }

        /**
         * 编辑器要求返回的数据结构
         */
        $data = [
            'url' => $newUrl,
            'originalURL' => $originalUrl,
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Post("/default/img", name="admin.upload.default_img")
     */
    public function uploadDefaultImageAction()
    {
        $service = new UploadService();

        $items = [];

        $items['user_avatar'] = $service->uploadDefaultUserAvatar();
        $items['course_cover'] = $service->uploadDefaultCourseCover();
        $items['slide_cover'] = $service->uploadDefaultSlideCover();
        $items['vip_cover'] = $service->uploadDefaultVipCover();

        foreach ($items as $key => $item) {
            $msg = sprintf('上传文件失败: %s', $key);
            if (!$item) {
                return $this->jsonError(['msg' => $msg]);
            }
        }

        return $this->jsonSuccess(['msg' => '上传文件成功']);
    }

    /**
     * @Post("/tmp/file", name="admin.upload.tmp_file")
     */
    public function uploadTmpFileAction()
    {
        $service = new StorageService();

        $file = $service->uploadTempFile();

        if (!$file) {
            return $this->jsonError(['msg' => '上传文件失败']);
        }

        return $this->jsonSuccess(['file' => $file]);
    }

    /**
     * @Post("/vod/sign", name="admin.upload.vod_sign")
     */
    public function vodSignatureAction()
    {
        $service = new VodService();

        $sign = $service->getUploadSignature();

        return $this->jsonSuccess(['sign' => $sign]);
    }

}
