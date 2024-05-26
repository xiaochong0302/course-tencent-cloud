<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */


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
            'id' => $file->id,
            'name' => $file->name,
            'url' => $service->getImageUrl($file->path),
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

}