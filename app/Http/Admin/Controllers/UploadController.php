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
use App\Validators\Validator as AppValidator;

/**
 * @RoutePrefix("/admin/upload")
 */
class UploadController extends Controller
{

    public function initialize()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser->id);
    }

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
     * @Post("/content/img", name="admin.upload.content_img")
     */
    public function uploadContentImageAction()
    {
        $service = new StorageService();

        $file = $service->uploadContentImage();

        if (!$file) {
            return $this->jsonError([
                'message' => '上传文件失败',
                'error' => 1,
            ]);
        }

        return $this->jsonSuccess([
            'url' => $service->getImageUrl($file->path),
            'error' => 0,
        ]);
    }

    /**
     * @Post("/default/img", name="admin.upload.default_img")
     */
    public function uploadDefaultImageAction()
    {
        $service = new UploadService();

        $items = [];

        $items['category_icon'] = $service->uploadDefaultCategoryIcon();
        $items['user_avatar'] = $service->uploadDefaultUserAvatar();
        $items['article_cover'] = $service->uploadDefaultArticleCover();
        $items['course_cover'] = $service->uploadDefaultCourseCover();
        $items['package_cover'] = $service->uploadDefaultPackageCover();
        $items['topic_cover'] = $service->uploadDefaultTopicCover();
        $items['slide_cover'] = $service->uploadDefaultSlideCover();
        $items['gift_cover'] = $service->uploadDefaultGiftCover();
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
     * @Post("/credentials", name="admin.upload.credentials")
     */
    public function credentialsAction()
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
