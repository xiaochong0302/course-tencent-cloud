<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;

class CourseUser extends Service
{

    use CourseUserTrait;
    use AccountSearchTrait;

    public function getSourceTypes()
    {
        return CourseUserModel::sourceTypes();
    }

    public function create($id)
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($id);

        $user = $validator->checkUser($post['user_id']);

        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $sourceType = CourseUserModel::SOURCE_MANUAL;

        $this->assignUserCourse($course, $user, $expiryTime, $sourceType);
    }

    public function getUsers($id)
    {
        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['course_id'] = $course->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListBuilder();

            $items = $pager->items->toArray();
            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
