<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\CourseList as UserCourseListService;

class CourseList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserCourseListService();

        return $service->handle($user->id);
    }

}
