<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Common as CommonService;
use App\Services\MyStorage as MyStorageService;
use App\Validators\Validator as AppValidator;

/**
 * @RoutePrefix("/upload")
 */
class UploadController extends Controller
{

    public function initialize()
    {
        $commonService = new CommonService();

        $authInfo = $commonService->getAuthInfo();

        $validator = new AppValidator();

        $validator->checkAuthInfo($authInfo);
    }

    /**
     * @Post("/avatar/img", name="home.upload.avatar_img")
     */
    public function uploadAvatarImageAction()
    {
        $service = new MyStorageService();

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
