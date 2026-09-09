<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Course\CourseList as CourseListService;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class CourseList extends LogicService
{

    use UserTrait;

    public function handle(int $id): PagerRepoInterface
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();
        $params = $pagerQuery->getParams();

        $params['teacher_id'] = $user->id;

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        $service = new CourseListService();

        return $service->handleCourses($pager);
    }

}
