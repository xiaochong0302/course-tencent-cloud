<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\Teacher\CourseList as CourseListService;

class CourseList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new CourseListService();

        return $service->handle($user->id);
    }

}
