<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\UserInfo as UserInfoService;

class TeacherInfo extends LogicService
{

    public function handle($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }

}
