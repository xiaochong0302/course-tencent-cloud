<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\StudyCourseList as UserStudyCourseListService;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class StudyCourseList extends LogicService
{

    public function handle(): PagerRepoInterface
    {
        $user = $this->getLoginUser();

        $service = new UserStudyCourseListService();

        return $service->handle($user->id);
    }

}
