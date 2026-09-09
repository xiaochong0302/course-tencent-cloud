<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class UserCourseStudy extends Service
{

    use CourseUserTrait;

    public function getCourses(int $userId): PagerRepoInterface
    {
        $validator = new CourseUserValidator();

        $user = $validator->checkUser($userId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new CourseUserListBuilder();

            $items = $pager->getItems()->toArray();
            $pipeA = $builder->handleCourses($items);
            $pipeB = $builder->objects($pipeA);

            $pager->setItems($pipeB);
        }

        return $pager;
    }

}
